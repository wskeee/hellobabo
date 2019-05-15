<?php

namespace apiend\modules\v1\actions\wx_pay;

use common\models\platform\Goods;
use common\models\platform\Order;
use common\models\platform\OrderActionLog;
use EasyWeChat\Payment\Application;
use Exception;
use Yii;
use yii\base\Action;

/**
 * 支付回调
 * @var $order Order
 */
class PayCb extends Action {

    public function run() {
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

            $tran = Yii::$app->db->beginTransaction();
            try {
                if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                    // 用户是否支付成功
                    if ($message['result_code'] === 'SUCCESS') {
                        $order->pay_code = 'weixin';
                        $order->pay_sn = $message['transaction_id'];
                        $order->pay_at = time(); // 更新支付时间为当前时间
                        $order->order_status = Order::STATUS_PAYED;
                        $order->start_at = time();
                        $order->end_at = time() + $order->getUseDuration(); //格式=pwd_时长                       
                        $order->password = $order->makePwd();

                        $order->goods->status = Goods::STATUS_RUNING;
                        $order->goods->save();

                        OrderActionLog::saveLog([$order->id], '支付成功', "支付方式：{$order->pay_code}");

                        // 用户支付失败
                    } elseif ($message['result_code'] === 'FAIL') {
                        $order->order_status = Order::STATUS_PAY_FAIL;
                        OrderActionLog::saveLog([$order->id], '支付失败', "{$message['err_code']}\n{$message['err_code_des']}");
                    }
                } else {
                    return $fail('通信失败，请稍后再通知我');
                }

                $order->save(); // 保存订单
                $tran->commit();
                return true; // 返回处理完成
            } catch (Exception $ex) {
                $tran->rollBack();
                return $fail('通信失败，请稍后再通知我');
            }
        });

        return $response; // return $response;
        //return new Response(Response::CODE_COMMON_OK);
    }

}
