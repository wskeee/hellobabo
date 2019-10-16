<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\goods\GoodsScene;
use common\models\order\OrderGoodsScene;
use common\utils\MysqlUtil;
use Yii;
use yii\db\Exception;

/**
 * 保存用户选定的场景
 *
 * @author Administrator
 */
class PresentSaveOrderGoodsScene extends BaseAction
{
    /* 必须参数 */

    protected $requiredParams = ['order_goods_id', 'scene_group_id'];

    public function run()
    {
        $order_goods_id = $this->getSecretParam('order_goods_id');
        $scene_group_id = $this->getSecretParam('scene_group_id');

        //查出必选场景合并用户选择的场景
        $scenes = GoodsScene::find()
                ->where(['group_id' => $scene_group_id])
                ->orderBy(['sort_order' => SORT_ASC])
                ->all();

        $rows = [];
        $index = 0;
        /* @var $scene GoodsScene */
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
