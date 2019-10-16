<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\goods\GoodsMaterial;
use common\models\goods\GoodsMaterialValue;
use common\models\goods\GoodsScene;
use common\models\order\OrderGoods;
use common\models\order\OrderGoodsMaterial;
use common\models\order\OrderGoodsScene;
use common\models\User;
use Exception;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * 初始绘本准备
 * 返回绘本素材和场景
 */
class InitReady extends BaseAction
{
    /* 必须参数 */

    protected $requiredParams = ['order_goods_id'];

    public function run()
    {
        /* @var $user User */
        $user = Yii::$app->user->identity;

        //订单
        $model = OrderGoods::findOne(['id' => $this->getSecretParam('order_goods_id'), 'created_by' => $user->id]);
        //所有绘本素材
        $goodsMaterials = GoodsMaterial::find()->where(['goods_id' => $model->goods_id, 'is_del' => 0])->all();
        //初始默认值
        $material_query = OrderGoodsMaterial::find()->where(['order_goods_id' => $model->id, 'is_del' => 0]);
        $scene_query = OrderGoodsScene::find()->where(['order_goods_id' => $model->id, 'immutable' => 0, 'is_del' => 0])->orderBy(['sort_order' => SORT_ASC]);

        //已经选择的素材
        $orderGoodsMaterials = $material_query->all();

        if (count($orderGoodsMaterials) == 0) {
            $this->initDefault($model);

            // 初始后重新赋值
            $orderGoodsMaterials = $material_query->all();
        }

        $orderGoodsMaterials = ArrayHelper::index($orderGoodsMaterials, 'material_id');
        //已经选择的场景
        $orderGoodsScenes = $scene_query->all();

        return new Response(Response::CODE_COMMON_OK, null, [
            'orderGoods' => $model,
            'goodsMaterials' => $goodsMaterials,
            'orderGoodsMaterials' => $orderGoodsMaterials,
            'orderGoodsScenes' => $orderGoodsScenes,
        ]);
    }

    /**
     * 订单商品
     * 
     * @param OrderGoods $order_goods_model
     */
    private function initDefault($order_goods_model)
    {
        $tran = \Yii::$app->db->beginTransaction();
        try {
            $this->initDefaultMaterial($order_goods_model);
            $this->initDefaultScene($order_goods_model);
            $tran->commit();
        } catch (Exception $ex) {
            $tran->rollBack();
            throw $ex;
        }
    }

    /**
     * 初始默认素材
     * @param OrderGoods $order_goods_model
     */
    private function initDefaultMaterial($order_goods_model)
    {
        $query = (new Query())
                ->select([
                    'Material.id material_id',
                    'MaterialValue.id value_id',
                    'MaterialValue.name value_name',
                    'MaterialValue.effect_url value_effect_url',
                    'MaterialValue.source_url value_source_url',
                    'MaterialValue.des value_des',
                ])
                ->from(['Material' => GoodsMaterial::tableName()])
                ->leftJoin(['MaterialValue' => GoodsMaterialValue::tableName()], 'MaterialValue.material_id = Material.id')
                ->where([
            'Material.goods_id' => $order_goods_model->goods_id,
            'Material.is_del' => 0,
            'MaterialValue.is_del' => 0,
            'MaterialValue.is_selected' => 1,
        ]);
        $material_values = $query->all();

        $rows = [];
        foreach ($material_values as $material_value) {
            $rows [] = [
                $order_goods_model->id,
                $material_value['material_id'],
                $material_value['value_id'],
                $material_value['value_name'],
                $material_value['value_effect_url'],
                $material_value['value_source_url'],
                $material_value['value_des'],
            ];
        }

        Yii::$app->db->createCommand()->batchInsert(OrderGoodsMaterial::tableName(), [
            'order_goods_id',
            'material_id',
            'value_id',
            'value_name',
            'value_effect_url',
            'value_source_url',
            'value_des',
                ], $rows)->execute();
    }

    /**
     * 初始默认场景
     * 
     * @param OrderGoods $order_goods_model
     */
    private function initDefaultScene($order_goods_model)
    {
        $query = (new Query())
                        ->select([
                            'id scene_id',
                            'name',
                            'effect_url',
                            'demo_url',
                            'source_url',
                            'is_required',
                            'des',
                            'immutable',
                        ])
                        ->from(['Scene' => GoodsScene::tableName()])
                        ->where([
                            'goods_id' => $order_goods_model->goods_id,
                            'is_selected' => 1,
                            'is_del' => 0
                        ])->orderBy(['sort_order' => SORT_ASC]);

        $scenes = $query->all();
        $rows = [];
        $sort_order = 0;
        $select_scene_num = 0;

        /* @var $scene GoodsScene */
        foreach ($scenes as $scene) {
            if ($scene['immutable'] || $select_scene_num < $order_goods_model->scene_num) {
                $rows [] = [
                    $order_goods_model->id,
                    $scene['scene_id'],
                    $scene['name'],
                    $scene['effect_url'],
                    $scene['demo_url'],
                    $scene['source_url'],
                    $scene['is_required'],
                    $scene['des'],
                    $scene['immutable'],
                    $sort_order++,
                ];

                if (!$scene['immutable']) {
                    $select_scene_num++;
                }
            }
        }
        Yii::$app->db->createCommand()->batchInsert(OrderGoodsScene::tableName(), [
            'order_goods_id',
            'scene_id',
            'name',
            'effect_url',
            'demo_url',
            'source_url',
            'is_required',
            'des',
            'immutable',
            'sort_order'
                ], $rows)->execute();
    }

}
