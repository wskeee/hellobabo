<?php

namespace console\controllers;

use common\models\platform\Goods;
use common\models\platform\Order;
use common\models\platform\OrderActionLog;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * 订单定时任务
 *
 * @author Administrator
 */
class OrderController extends Controller {

    /**
     * 检查订单是否在限期内完成支付
     * 
     * 如果订单创建后未在5分钟内支付，即订单作废
     */
    public function actionCheckPlay() {
        //
        //do something
        //
        $result = (new Query())
                ->select(['id', 'goods_id'])
                ->from(['Order' => Order::tableName()])
                ->where(['order_status' => Order::STATUS_WAITPAY])
                ->andFilterWhere(['<', 'created_at', strtotime("-5 minute", time())])
                ->all();
        
        if (count($result) > 0) {
            //作废订单
            Order::updateAll([
                'order_status' => Order::STATUS_INVALID,
                'updated_at' => time(),
                    ], ['id' => ArrayHelper::getColumn($result, 'id')]);

            //重置设备状态 
            Goods::updateAll(['status' => Goods::STATUS_FREE], ['id' => ArrayHelper::getColumn($result, 'goods_id')]);

            OrderActionLog::saveLog(ArrayHelper::getColumn($result, 'id'), '过期作废', '订单过期，系统自动作废');
        }

        return ExitCode::OK;
    }

    /**
     * 检查订单是否已经完成
     * 
     */
    public function actionCheckConfirm() {
        //
        //do something
        //
        $orders = Order::find()
                ->where(['order_status' => Order::STATUS_PAYED])    //待确认
                ->andWhere(['<=', 'end_at', time()])
                ->all();

        /* @var $order Order */
        foreach ($orders as $order) {
            $order->finish();
        }

        OrderActionLog::saveLog(ArrayHelper::getColumn($orders, 'id'), '订单确认', '系统自动确认！');
        return ExitCode::OK;
    }

}
