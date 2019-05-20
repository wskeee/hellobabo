<?php

namespace apiend\modules\v1\actions\wx_pay;

use common\models\order\Order;
use common\models\order\OrderAction;
use EasyWeChat\Payment\Application;
use Exception;
use Yii;
use yii\base\Action;

/**
 * 支付回调
 * @var $order Order
 */
class PayCb extends Action
{

    public function run()
    {
        /* @var $payment Application */
        $payment = Yii::$app->wechat->payment;
        $response = $payment->handlePaidNotify(function($message, $fail)use($payment) {
            /* @var $order Order */
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $order = Order::findOne(['order_sn' => $message['out_trade_no']]);

            if (!$order || $order->order_status != Order::STATUS_WAITPAY) { // 如果订单不存在 或者 订单已经支付过了
                return true; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }

            ///////////// <- 建议在这里调用微信的【订单查询】接口查一下该笔订单的情况，确认是已经支付 /////////////
            //Yii::debug($payment->order->queryByOutTradeNumber($order->order_sn));

            if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                // 用户是否支付成功
                if ($order->pay($message)) {
                    return true; // 返回处理完成
                } else {
                    return $fail('通信失败，请稍后再通知我');
                }
            } else {
                return $fail('通信失败，请稍后再通知我');
            }
        });

        return $response; // return $response;
        //return new Response(Response::CODE_COMMON_OK);
    }

}
