<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\goods\Goods;
use common\models\goods\GoodsSpecPrice;
use common\models\order\Order;
use common\models\order\OrderAction;
use common\models\UserAddress;
use Yii;

/**
 * 下单
 *
 * @author Administrator
 */
class CreateOrder extends BaseAction
{
    /* 必须参数 */

    protected $requiredParams = ['goods_id', 'gsp_id', 'address_id'];

    public function run()
    {
        $user_id = Yii::$app->user->id;
        $goods_num = $this->getSecretParam('goods_num', 1); //购买数量 
        $user_note = $this->getSecretParam('user_note', ''); //留言
        $recommend_by = $this->getSecretParam('recommend_by', null); //推挤人ID
        $goods = Goods::findOne(['id' => $this->getSecretParam('goods_id')]); //绘本商品
        $spec_price = GoodsSpecPrice::findOne(['id' => $this->getSecretParam('gsp_id')]); //价格
        $address_id = UserAddress::findOne(['id' => $this->getSecretParam('address_id')]); //地址

        /* 检查订单状态 */
        if (!$goods || $goods->status != Goods::STATUS_PUBLISHED) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => \Yii::t('app', 'Goods')]);
        } else if ($spec_price->store_count <= 0) {
            return new Response(Response::CODE_ORDER_STORE_COUNT_OUT);
        }

        $order = new Order([
            'order_sn' => Order::getRandomSN(),
            //商品
            'goods_id' => $goods->id,
            'goods_name' => $goods->goods_name, //商品名
            'goods_price' => $spec_price->goods_price, //商品价格
            'goods_num' => $goods_num, //购买数量
            'spec_id' => $spec_price->id, //价格ID
            'spec_key' => $spec_price->spec_key, //价格项ID
            'spec_key_name' => $spec_price->spec_key_name, //价格项名
            'order_amount' => $spec_price->goods_price * $goods_num, //订单总额使用套餐价格
            //推荐
            'is_recommend' => $recommend_by != null ? 1 : 0, //是否为推荐订单
            'recommend_by' => $recommend_by, //推挤人ID
            //收货地址
            'address_id' => $address_id, //地址ID
            'user_note' => $user_note, //留言
            'consignee' => $address_id->consignee, //收货人
            'zipcode' => $address_id->zipcode,
            'phone' => $address_id->phone,
            'province' => $address_id->province,
            'city' => $address_id->city,
            'district' => $address_id->district,
            'town' => $address_id->town,
            'address' => $address_id->address,
            'created_by' => $user_id,
        ]);

        if ($order->save()) {
            OrderAction::saveLog($order->id, '创建订单', '');
            return new Response(Response::CODE_COMMON_OK, null, $order);
        } else {
            return new Response(Response::CODE_ORDER_CREATE_FAILED, '下单失败', $order->getErrorSummary(true));
        }
    }

}
