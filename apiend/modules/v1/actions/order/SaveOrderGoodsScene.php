<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\goods\GoodsScene;
use common\models\order\OrderGoods;
use common\models\order\OrderGoodsScene;
use common\utils\MysqlUtil;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

/**
 * 保存用户选定的场景
 *
 * @author Administrator
 */
class SaveOrderGoodsScene extends BaseAction
{
    /* 必须参数 */

    protected $requiredParams = ['order_goods_id', 'scenes_ids'];

    public function run()
    {
        $order_goods_id = $this->getSecretParam('order_goods_id');
        $order_goods_model = OrderGoods::findOne(['id' => $order_goods_id]);
        //用户选择的场景数据
        $scenes_ids = explode(',', $this->getSecretParam('scenes_ids', []));
        $scenes_ids_index = array_flip($scenes_ids);
        //查出必选场景合并用户选择的场景
        $scenes = GoodsScene::find()
                ->orWhere(['id' => $scenes_ids])
                ->orWhere(['goods_id' => $order_goods_model->goods_id, 'immutable' => 1, 'is_selected' => 1, 'is_del' => 0])
                ->orderBy(['sort_order' => SORT_ASC])
                ->all();
        $scenes_pos = ArrayHelper::index($scenes, null, 'pos');
        // 按用户调整后顺序重新排序
        uasort($scenes_pos['center'], function($a, $b)use($scenes_ids_index) {
            return $scenes_ids_index[$a->id] > $scenes_ids_index[$b->id];
        });

        $rows = [];
        $index = 0;
        /* @var $scene GoodsScene */
        foreach ($scenes_pos as $scenes) {
            foreach ($scenes as $scene) {
                $rows [] = [
                    $order_goods_id,
                    $scene->id,
                    $scene->name,
                    $scene->effect_url,
                    $scene->demo_url,
                    $scene->source_url,
                    $index++,
                    $scene->immutable,
                    $scene->is_required,
                    $scene->des,
                    0,
                ];
            }
        }
    
        $tran = Yii::$app->db->beginTransaction();
        try {
            //先把原先已经选择的记录清除
            OrderGoodsScene::updateAll(['is_del' => 1], ['order_goods_id' => $order_goods_id]);
            //重新插入新记录
            MysqlUtil::batchInsertDuplicateUpdate(OrderGoodsScene::tableName(), [
                'order_goods_id', 'scene_id', 'name', 'effect_url', 'demo_url', 'source_url', 'sort_order', 'immutable', 'is_required', 'des', 'is_del'], $rows, ['sort_order', 'is_del']);
            $tran->commit();
            return new Response(Response::CODE_COMMON_OK);
        } catch (Exception $ex) {
            return new Response(Response::CODE_COMMON_SAVE_DB_FAIL, null, $ex);
        }
    }

}
