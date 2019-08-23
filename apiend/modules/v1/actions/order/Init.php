<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\goods\GoodsScenePage;
use common\models\order\OrderGoods;
use common\models\order\OrderGoodsAction;
use common\models\order\OrderGoodsScene;
use common\models\order\OrderGoodsScenePage;
use Yii;
use yii\db\Query;

/**
 * 设置绘本已初始
 * 已完成素材和场景的选择
 */
class Init extends BaseAction
{
    /* 必须参数 */

    protected $requiredParams = ['order_goods_id'];

    public function run()
    {
        $order_goods_id = $this->getSecretParam('order_goods_id');

        $model = OrderGoods::findOne(['id' => $order_goods_id]);
        $model->status = OrderGoods::STATUS_UPLOAD_PIC; //设置为待上图状态
        $model->init_at = time();   //记录初始时间

        $tran = Yii::$app->db->beginTransaction();
        try {
            if ($model->save()) {
                $this->initPage($model);
                OrderGoodsAction::saveLog($model->id, '初始绘本', '用户初始化绘本');
                $tran->commit();
                return new Response(Response::CODE_COMMON_OK, null, $model);
            } else {
                throw new Exception(implode('', $model->getErrorSummary(true)));
            }
        } catch (\Exception $ex) {
            return new Response(Response::CODE_COMMON_SAVE_DB_FAIL, $ex->getMessage());
        }
    }

    /**
     * 初始分页数据
     * 
     * @param OrderGoods $model
     */
    private function initPage($model)
    {
        $query = (new Query())
                        ->select([
                            'OrderGoodsScene.id order_goods_scene_id', 'OrderGoodsScene.sort_order scene_sort_order',
                            'ScenePage.*'
                        ])
                        ->from(['OrderGoodsScene' => OrderGoodsScene::tableName()])
                        ->leftJoin(['ScenePage' => GoodsScenePage::tableName()], 'ScenePage.scene_id = OrderGoodsScene.scene_id')
                        ->where([
                            'OrderGoodsScene.order_goods_id' => $model->id,
                            'OrderGoodsScene.is_del' => 0,
                            'ScenePage.is_del' => 0,
                        ])->orderBy(['scene_sort_order' => SORT_ASC, 'pos' => SORT_ASC]);
        
        $pages = $query->all();
        $rows = [];
        $sort_order = 0;
        foreach ($pages as $page) {
            $rows[] = [
                $model->id,
                $page['order_goods_scene_id'],
                $page['id'],
                $page['source_id'],
                $page['name'],
                $page['effect_url'],
                $page['source_url'],
                $page['pos'],
                $page['is_required'],
                $page['des'],
                $sort_order++,
            ];
        }
        // 清除旧数据
        OrderGoodsScenePage::updateAll(['is_del' => 1], ['order_goods_id' => $model->id]);

        Yii::$app->db->createCommand()->batchInsert(OrderGoodsScenePage::tableName(), [
            'order_goods_id', 'order_goods_scene_id', 'page_id', 'source_id', 'name', 'effect_url', 'source_url', 'pos', 'is_required', 'des', 'sort_order'
                ], $rows)->execute();
    }

}
