<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\goods\GoodsScene;
use common\models\goods\SceneGroup;
use common\models\order\OrderGoods;
use common\models\order\OrderGoodsScene;
use common\models\User;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * 礼物类绘本
 * 初始绘本准备
 * 返回绘本素材和场景
 */
class PresentInitReady extends BaseAction
{
    /* 必须参数 */

    protected $requiredParams = ['order_goods_id'];

    public function run()
    {
        /* @var $user User */
        $user = Yii::$app->user->identity;
        $order_goods_id = $this->getSecretParam('order_goods_id');
        $current_group_id = "";
        //订单
        $model = OrderGoods::findOne(['id' => $order_goods_id, 'created_by' => $user->id]);

        if (!$model || $model->status != OrderGoods::STATUS_INIT) {
            return new Response(Response::CODE_COMMON_FORBIDDEN);
        }

        $scene_groups = SceneGroup::find()->where(['relate_id' => $model->goods_id, 'is_del' => 0])->all();
        $scenes = GoodsScene::find()->where(['goods_id' => $model->goods_id, 'is_del' => 0])->orderBy(['sort_order' => SORT_ASC])->all();
        $hasDoneScene = OrderGoodsScene::findOne(['order_goods_id' => $order_goods_id]);
        
        if ($hasDoneScene != null) {
            $current_group_id = $hasDoneScene->scene->group_id;
        }else{
            $current_group_id = $scene_groups[0]->id;
        }

        return new Response(Response::CODE_COMMON_OK, null, [
            'orderGoods' => $model,
            'current_group_id' => $current_group_id,
            'scene_groups' => $scene_groups,
            'scenes' => ArrayHelper::index($scenes, null, 'group_id'),
        ]);
    }

}
