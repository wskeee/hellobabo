<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\order\Order;
use common\models\User;
use EasyWeChat\Payment\Application;
use Yii;

/**
 * 返回订单详情（未付款情况下，会查询微信订单情况）
 *
 * @author Administrator
 */
class CheckPay extends BaseAction
{
    /* 必须参数 */

    protected $requiredParams = ['order_id'];

    public function run()
    {
        /* @var $user User */
        $user = Yii::$app->user->identity;

        $order = Order::findOne(['id' => $this->getSecretParam('order_id'), 'created_by' => $user->id]);

        if ($order) {
            if ($order->order_status == Order::ORDER_STATUS_WAIT_PAY) {
                /*
                 // 模拟支付功能

                $message = [
                    'return_code' => 'SUCCESS',
                    'result_code' => 'SUCCESS',
                    'trade_state' => 'SUCCESS',
                    'transaction_id' => strtoupper(md5(time())),
                ];

                $order->pay(true, $message);
                return new Response(Response::CODE_COMMON_OK, null, $order);*/


                /* @var $payment Application */
                $payment = Yii::$app->wechat->payment;
                $message = $payment->order->queryByOutTradeNumber($order->order_sn);
                //未付款状态下，去微信查询订单状态
                if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                    if ($message['result_code'] === 'SUCCESS' && $message['trade_state'] === 'SUCCESS') {
                        $order->pay(true, $message);
                    } else if ($message['result_code'] === 'SUCCESS' && $message['trade_state'] === 'FAIL') {
                        $order->pay(false, $message);
                    }
                }
            }
            return new Response(Response::CODE_COMMON_OK, null, $order);
        } else {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => \Yii::t('app', 'Order')]);
        }
    }

}
