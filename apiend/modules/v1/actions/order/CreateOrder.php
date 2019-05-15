<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\platform\Goods;
use common\models\platform\Order;
use common\models\platform\Pricing;
use common\models\User;
use EasyWeChat\Payment\Application;
use Yii;
use yii\base\ErrorException;

/**
 * 下单
 *
 * @author Administrator
 */
class CreateOrder extends BaseAction {

    public function run() {
        if (!$this->verify()) {
            return $this->verifyError;
        }

        /* 检查必须参数 */
        if ($notfounds = $this->checkRequiredParams($this->getSecretParams(), ['goods_sn', 'pricing_id'])) {
            return new Response(Response::CODE_COMMON_MISS_PARAM, null, null, ['param' => implode(',', $notfounds)]);
        }

        $user_id = Yii::$app->user->id;
        $goods = Goods::findOne(['goods_sn' => $this->getSecretParam('goods_sn')]);
        $pricing = Pricing::findOne(['id' => $this->getSecretParam('pricing_id')]);

        /* 检查订单状态 */
        if (!$goods || empty($goods->agency_id) || empty($goods->merchant_id)) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => \Yii::t('app', 'Goods')]);
        } else if ($goods->status == Goods::STATUS_LOCK) {
            return new Response(Response::CODE_ORDER_CREATE_FAILED, '设备已锁定,请到订单列表进入订单完成支付！');
        } else if ($goods->status != Goods::STATUS_FREE) {
            if ($goods->status == Goods::STATUS_RUNING) {
                //查出正在运行的订单
                $order = Order::find()
                        ->where(['goods_sn' => $goods->goods_sn])
                        ->andWhere(['>', 'end_at', time()])
                        ->one();
                if ($order) {
                    //正在清理中
                    return new Response(Response::CODE_ORDER_CREATE_FAILED, '设备刚刚完成充电，正在打扫现场，稍等一分钟!');
                }
            }
            return new Response(Response::CODE_ORDER_CREATE_FAILED, \Yii::t('app', 'Goods') . Goods::$statusKeyMap[$goods->status]);
        }

        $order = new Order([
            'order_sn' => Order::getRandomSN(),
            'agency_id' => $goods->agency_id,
            'merchant_id' => $goods->merchant_id,
            'goods_id' => $goods->id,
            'goods_sn' => $goods->goods_sn,
            'goods_name' => $pricing->name, //商品名使用套餐名
            'goods_price' => $pricing->price, //商品价格使用套餐价格
            'goods_data' => $pricing->value, //商品附加值 
            'order_amount' => $pricing->price, //订单总额使用套餐价格
            'created_by' => $user_id,
        ]);

        if ($order->save()) {
            //设备锁定
            $order->goods->status = Goods::STATUS_LOCK;
            $order->goods->save();
            //微信下单
            /* @var $user User */
            $user = Yii::$app->user->identity;
            // 支付参数
            $orderData = [
                'openid' => $user->auths->identifier,
                'body' => "共创充电 {$order->goods_name}",
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
