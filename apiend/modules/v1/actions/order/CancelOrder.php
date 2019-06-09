<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\order\Order;
use common\models\order\OrderAction;
use Yii;

/**
 * 取消订单
 *
 * @author Administrator
 */
class CancelOrder extends BaseAction
{

    protected $requiredParams = ['order_id'];

    public function run()
    {
        $order_id = $this->getSecretParam('order_id');
        $order = Order::findOne(['id' => $order_id]);

        if (empty($order) || !$order->getIsValid()) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => Yii::t('app', 'Order')]);
        } else if ($order->getIsPlyed()) {
            //状态不为待支付和支付失败,算重复支付
            return new Response(Response::CODE_ORDER_CANCEL_FAILED, '已支付订单无法取消');
        }
        //修改为已经取消状态
        $order->order_status = Order::ORDER_STATUS_CANCELED;
        
        $tran = Yii::$app->db->beginTransaction();
        try {
            if ($order->save()) {
                OrderAction::saveLog([$order_id], '订单取消', '客户自行取消订单');
                $tran->commit();
                return new Response(Response::CODE_COMMON_OK);
            } else {
                return new Response(Response::CODE_ORDER_CANCEL_FAILED, null, $order->getErrorSummary(true));
            }
        } catch (\Exception $ex) {
            $tran->rollBack();
            return new Response(Response::CODE_ORDER_CANCEL_FAILED, null, $ex->getMessage());
        }
    }

}
