<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\goods\Goods;
use common\models\goods\GoodsSpecPrice;
use common\models\order\Order;
use common\models\User;
use common\models\UserAddress;
use EasyWeChat\Payment\Application;
use Yii;

/**
 * 下单
 *
 * @author Administrator
 */
class CreateOrder extends BaseAction
{

    public function run()
    {
        if (!$this->verify()) {
            return $this->verifyError;
        }

        /* 检查必须参数 */
        if ($notfounds = $this->checkRequiredParams($this->getSecretParams(), ['goods_id', 'gsp_id' ,'address_id'])) {
            return new Response(Response::CODE_COMMON_MISS_PARAM, null, null, ['param' => implode(',', $notfounds)]);
        }

        $user_id = Yii::$app->user->id;
        $goods_num = $this->getSecretParam('goods_num', 1);//购买数量 
        $user_note = $this->getSecretParam('user_note', '');//留言
        $recommend_by = $this->getSecretParam('recommend_by', null);//推挤人ID
        $goods = Goods::findOne(['id' => $this->getSecretParam('goods_id')]);//绘本商品
        $spec_price = GoodsSpecPrice::findOne(['id' => $this->getSecretParam('gsp_id')]);//价格
        $address_id = UserAddress::findOne(['id' => $this->getSecretParam('address_id')]);//地址

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
            'user_note' => $user_note, //留言
            'consignee' => $address_id->consignee,//收货人
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
            //微信下单
            /* @var $user User */
            $user = Yii::$app->user->identity;
            // 支付参数
            $orderData = [
                'openid' => $user->auths->identifier,
                'body' => "Hellobabo {$order->goods_name}",
                'out_trade_no' => $order->order_sn,
                'total_fee' => 1, //intval(floatval($order->order_amount)*100),  //传参以分为单位
                'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
            ];

            /* @var $payment Application */
            $payment = Yii::$app->wechat->payment;
            //{
            //    "return_code": "SUCCESS",
            //    "return_msg": "OK",
            //    "appid": "wx2421b1c4390ec4sb",
            //    "mch_id": "10000100",
            //    "nonce_str": "IITRi8Iabbblz1J",
            //    "openid": "oUpF8uMuAJO_M2pxb1Q9zNjWeSs6o",
            //    "sign": "7921E432F65EB8ED0CE9755F0E86D72F2",
            //    "result_code": "SUCCESS",
            //    "prepay_id": "wx201411102639507cbf6ffd8b0779950874",
            //    "trade_type": "JSAPI"
            //}
            $result = $payment->order->unify($orderData);
            Yii::debug($orderData);
            Yii::debug($result);
            if ($result['return_code'] == 'SUCCESS') {
                $prepayId = $result['prepay_id'];
                $config = $payment->jssdk->sdkConfig($prepayId);
            } else {
                return new Response(Response::CODE_ORDER_CREATE_FAILED, '微信支付异常, 请稍后再试', $result);
            }

            return new Response(Response::CODE_COMMON_OK, null, array_merge($config, ['order_sn' => $order->order_sn]));
        } else {
            return new Response(Response::CODE_ORDER_CREATE_FAILED, '下订失败', $order->getErrorSummary(true));
        }
    }

}
