<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\order\Order;

/**
 * 设置绘本已初始
 * 已完成素材和场景的选择
 */
class Init extends BaseAction
{
    /* 必须参数 */

    protected $requiredParams = ['order_id'];

    public function run()
    {
        $order_id = $this->getSecretParam('order_id');

        $order = Order::findOne(['id' => $order_id]);
        $order->order_status = Order::ORDER_STATUS_WAIT_UPLOAD_PIC; //设置为待上图状态
        $order->init_at = time();   //记录初始时间

        if ($order->save()) {
            OrderAction::saveLog($order->id, '初始绘本', '用户初始化绘本');
            return new Response(Response::CODE_COMMON_OK);
        } else {
            return new Response(Response::CODE_COMMON_SAVE_DB_FAIL, null, $order->getErrorSummary(true));
        }
    }

}
