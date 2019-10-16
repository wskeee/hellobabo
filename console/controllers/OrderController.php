<?php

namespace console\controllers;

use common\models\order\Groupon;
use common\models\order\GrouponRecord;
use common\models\order\Order;
use common\models\order\OrderAction;
use common\models\order\OrderGoods;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * 订单定时任务
 *
 * @author Administrator
 */
class OrderController extends Controller
{

    /**
     * 检查订单是否在限期内完成支付
     * 
     * 如果订单创建后未在5分钟内支付，即订单作废
     */
    public function actionCheckPlay()
    {
        //
        //do something
        //
        $result = (new Query())
                ->select(['id', 'goods_id'])
                ->from(['Order' => Order::tableName()])
                ->where(['order_status' => [Order::ORDER_STATUS_WAIT_PAY, Order::ORDER_STATUS_PAY_FAIL]])
                ->andFilterWhere(['<', 'created_at', strtotime("-2 hour", time())])
                ->all();

        if (count($result) > 0) {
            //作废订单
            Order::updateAll([
                'order_status' => Order::ORDER_STATUS_INVALID,
                'updated_at' => time(),
                    ], ['id' => ArrayHelper::getColumn($result, 'id')]);
            //作废商品
            OrderGoods::updateAll(['status' => OrderGoods::STATUS_INVALID], ['order_id' => ArrayHelper::getColumn($result, 'id')]);
            //作废和订单创建的团购
            Groupon::updateAll(['status' => Groupon::STATUS_INVALID], ['order_id' => ArrayHelper::getColumn($result, 'id')]);
            //作废团购记录
            GrouponRecord::updateAll(['status' => GrouponRecord::STATUS_INVALID], ['order_id' => ArrayHelper::getColumn($result, 'id')]);

            OrderAction::saveLog(ArrayHelper::getColumn($result, 'id'), '过期作废', '订单过期，系统自动作废');
        }

        return ExitCode::OK;
    }

    /**
     * 检查订单是否已经完成
     * 
     */
    public function actionCheckConfirm()
    {
        //
        //do something
        //
        $orders = Order::find()
                ->where(['order_status' => Order::ORDER_STATUS_WAIT_CONFIRM])    //待确认
                ->andFilterWhere(['<', 'created_at', strtotime("-7 day", time())])
                ->all();

        /* @var $order Order */
        foreach ($orders as $order) {
            $order->finish();
        }

        OrderAction::saveLog(ArrayHelper::getColumn($orders, 'id'), '订单确认', '系统自动确认！');
        return ExitCode::OK;
    }

}
