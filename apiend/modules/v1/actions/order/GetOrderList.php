<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\order\Order;
use common\models\order\OrderGoods;
use Yii;
use yii\db\Query;

class GetOrderList extends BaseAction
{

    public function run()
    {
        $order_status = $this->getSecretParam('order_status', '');
        $order_status_arr = explode(',', urldecode($order_status));
        $orders = (new Query())
                ->select([
                    'Order.id', 'Order.order_sn', 'Order.order_status', 'Order.created_at', 'Order.order_amount',
                    'OrderGoods.id order_goods_id','OrderGoods.goods_name','OrderGoods.goods_img', 'OrderGoods.spec_key_name',
                    
                ])
                ->from(['Order' => Order::tableName()])
                ->leftJoin(['OrderGoods' => OrderGoods::tableName()],'Order.id = OrderGoods.order_id')
                ->where(['Order.created_by' => Yii::$app->user->id])
                ->andFilterWhere(['Order.order_status' => $order_status_arr])
                ->limit(50)
                ->orderBy(['Order.created_at' => SORT_DESC])          
                ->all();

        foreach ($orders as &$order) {
            $order['order_status_text'] = Order::$orderStatusNameMap[$order['order_status']];
            $order['created_time'] = date('Y-m-d H:i:s',$order['created_at']);
        }

        return new Response(Response::CODE_COMMON_OK, null, [
            'order_status' => $order_status,
            'orders' => $orders
        ]);
    }

}
