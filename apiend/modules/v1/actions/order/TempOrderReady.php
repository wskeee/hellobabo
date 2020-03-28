<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\components\redis\RedisService;
use common\models\goods\Goods;
use common\models\goods\GoodsMaterial;
use common\models\goods\GoodsMaterialValue;
use common\models\User;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * 获取临时订单初始配置
 *
 * @author Administrator
 */
class TempOrderReady extends BaseAction
{
    protected $requiredParams = ['goods_id'];

    public function run()
    {

        $goods_id = $this->getSecretParam('goods_id');
        $user_id = Yii::$app->user->id;
        /* @var User $user_model */
        $user_model = Yii::$app->user->identity;

        // 商品
        /* @var Goods $goods */
        $goods = Goods::find()->where(['id' => $goods_id])->one();
        if ($goods == null) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => '绘本']);
        }

        // 默认临时订单配置
        $time = time();
        $defaultTempOrder = [
            'step' => 1,
            'user_id' => $user_id,
            'user_name' => $user_model->nickname,
            'goods_id' => $goods_id,
            'goods_type' => $goods->type,
            'goods_name' => $goods->goods_name,
            'material_id' => "",
            'material_value_id' => "",
            'scenes' => '',
            'sign' => md5($time),   // 数据校难，防止下订单前修改订单
            "created_at" => $time,
            'updated_at' => $time,
        ];

        // 临时订单
        $tempOrder = GetTempOrder::getTempOrder($user_id, $goods_id);
        !$tempOrder && $tempOrder = $defaultTempOrder;

        // 素材和商品素材值
        $materials = ArrayHelper::index(GoodsMaterial::getMaterialByGoodsId($goods_id), 'id');
        $materialValues = GoodsMaterialValue::getGoodsMaterialValue($goods_id);

        $data = [
            'goods' => $goods,
            'materials' => $materials,
            'material_values' => $materialValues,
            'temp_order' => $tempOrder,
        ];

        return new Response(Response::CODE_COMMON_OK, null, $data);
    }

}
