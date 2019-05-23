<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\order\Order;
use common\models\User;
use EasyWeChat\Payment\Application;
use Yii;

/**
 * 订单支付
 *
 * @author wskeee
 */
class Pay extends BaseAction
{

    protected $requiredParams = ['order_id'];

    public function run()
    {
        $order = Order::findOne(['id' => $this->getSecretParam('order_id')]);

        if (empty($order) || !$order->getIsValid()) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => \Yii::t('app', 'Order')]);
        } else if ($order->getIsPlyed()) {
            //状态不为待支付和支付失败,算重复支付
            return new Response(Response::CODE_ORDER_PAY_REPEATED);
        }

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
        return new Response(Response::CODE_COMMON_OK, null, $config);
    }

}
