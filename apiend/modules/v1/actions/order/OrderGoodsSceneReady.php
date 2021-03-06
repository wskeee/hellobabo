<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\goods\GoodsScene;
use common\models\goods\SceneGroup;
use common\models\order\OrderGoods;
use common\models\order\OrderGoodsScene;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * 选择场景接口
 * 返回已选择数据及绘本的所有场景数据
 *
 * @author Administrator
 */
class OrderGoodsSceneReady extends BaseAction
{
    /* 必须参数 */

    protected $requiredParams = ['order_goods_id', 'goods_id'];

    public function run()
    {
        $order_goods_id = $this->getSecretParam('order_goods_id');
        $goods_id = $this->getSecretParam('goods_id');

        $model = OrderGoods::findOne(['id' => $order_goods_id]);

        if ($model == null) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => Yii::t('app', 'Order')]);
        }

        //场景列表
        $list = GoodsScene::find()->where([
                    'goods_id' => $goods_id,
                    'immutable' => 0,
                    'is_del' => 0,
                ])->all();

        //场景分组
        $groups = SceneGroup::find()
                ->where(['id' => ArrayHelper::getColumn($list, 'group_id'), 'is_del' => 0])
                ->all();

        //已选择场景
        $order_scenes = OrderGoodsScene::find()
                ->select(['scene_id', 'sort_order'])
                ->where(['order_goods_id' => $order_goods_id, 'immutable' => 0, 'is_del' => 0])
                ->orderBy(['sort_order' => SORT_ASC])
                ->all();


        return new Response(Response::CODE_COMMON_OK, null, [
            'scene_num' => $model->scene_num,
            'scenes' => ArrayHelper::index($list, null, 'group_id'),
            'groups' => $groups,
            'order_scenes' => $order_scenes,
        ]);
    }

}
