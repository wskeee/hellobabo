<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\platform\Order;
use common\models\platform\Pricing;
use Yii;

/**
 * 下单准备
 * 返回当前设备是否在运行中
 * 返回当前设备的套餐
 */
class Ready extends BaseAction {

    public function run() {
        if (!$this->verify()) {
            return $this->verifyError;
        }
        //当前下在运行的订单
        $orders = Order::find()
                ->select(['order_sn','goods_sn'])
                ->where(['order_status' => Order::STATUS_PAYED, 'created_by' => Yii::$app->user->id])
                ->asArray()
                ->all();
        //套餐
        $pricings = Pricing::findAll(['is_del' => 0]);
        
        return new Response(Response::CODE_COMMON_OK, null, [
            'orders' => $orders,
            'pricings' => $pricings,
        ]);
    }

}
