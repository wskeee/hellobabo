<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\order\Order;
use Yii;
use yii\db\Query;

class GetOrderList extends BaseAction
{

    public function run()
    {
        $order_status = $this->getSecretParam('order_status', '');

        if ($order_status == '_') {
            $order_status_arr = null;
        }else{
            $order_status_arr = explode(',', $order_status);
        }
        $orders = (new Query())
                ->select([
                    'Order.id', 'Order.order_sn', 'Order.order_status', 'Order.created_at', 'Order.goods_name', 'Order.order_amount',
                    'order.goods_img', 'Order.spec_key_name',
                ])
                ->from(['Order' => Order::tableName()])
                ->where(['Order.created_by' => Yii::$app->user->id])
                ->andFilterWhere(['Order.order_status' => $order_status_arr])
                ->limit(50)
                ->orderBy(['Order.created_at' => SORT_DESC])
                ->all();

        foreach ($orders as &$order) {
            $order['order_status_text'] = Order::$orderStatusNameMap[$order['order_status']];
            $order['created_time'] = date('Y-m-d H:i:s');
        }

        return new Response(Response::CODE_COMMON_OK, null, [
            'order_status' => $order_status,
            'orders' => $orders
        ]);
    }

}
