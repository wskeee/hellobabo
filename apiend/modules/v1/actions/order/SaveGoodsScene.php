<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\goods\GoodsScene;
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
class SaveGoodsScene extends BaseAction
{
    /* 必须参数 */

    protected $requiredParams = ['order_id', 'scenes'];

    public function run()
    {
        $order_id = $this->getSecretParam('order_id');
        //用户选择的场景数据
        $scenes_post = $this->getSecretParam('scenes');
        $scenes_post_sort = ArrayHelper::map($scenes_post, 'id', 'sort_order');

        $scenes = GoodsScene::findAll(['id' => ArrayHelper::getColumn($scenes_post, 'scene_id')]);
        $rows = [];
        /* @var $scene GoodsScene */
        foreach ($scenes as $scene) {
            $rows [] = [
                $order_id,
                $scene->id,
                $scene->name,
                $scene->effect_url,
                $scene->demo_url,
                $scene->source_url,
                $scenes_post_sort[$scene->id],
                $scene->is_required,
                $scene->des,
                0,
            ];
        }
        $tran = Yii::$app->db->beginTransaction();
        try {
            //先把原先已经选择的记录清除
            OrderGoodsScene::updateAll(['is_del' => 1], ['order_id' => $order_id]);
            //重新插入新记录
            MysqlUtil::batchInsertDuplicateUpdate(OrderGoodsScene::tableName(), [
                        'order_id', 'scene_id', 'name', 'effect_url', 'demo_url', 'source_url', 'sort_order', 'is_required', 'des', 'is_del'], $rows, ['sort_order', 'is_del'])
                    ->execute();
            $tran->commit();
            return new Response(Response::CODE_COMMON_OK);
        } catch (Exception $ex) {
            return new Response(Response::CODE_COMMON_SAVE_DB_FAIL, null, $ex);
        }
    }

}
