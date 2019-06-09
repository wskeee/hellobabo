<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\order\Order;
use common\models\system\Region;
use Yii;

class GetOrderDetail extends BaseAction
{
    /* 必须参数 */

    protected $requiredParams = ['order_id'];

    public function run()
    {
        $order_id = $this->getSecretParam('order_id', null);

        $order = Order::findOne(['id' => $order_id, 'created_by' => Yii::$app->user->id]);

        if ($order) {
            $order = $order->toArray([
                'id','order_sn','order_status','order_amount','goods_name','goods_img','spec_key_name',
                'user_note','consignee','phone','province','city','district','address',
                'created_at',
                'pay_at',
                'init_at',
                'upload_finish_at',
                'shipping_at',
                'confirm_at'
                ]);
            //转换
            $order['order_status_text'] = Order::$orderStatusNameMap[$order['order_status']];
            $order['address'] = implode("", Region::getRegionName([$order['province'],$order['city'],$order['district'],])).$order['address'];
            $order['created_time'] = date('Y-m-d H:i:s',$order['created_at']);
            $order['pay_time'] = date('Y-m-d H:i:s',$order['pay_at']);
            $order['init_time'] = date('Y-m-d H:i:s',$order['init_at']);
            $order['upload_finish_time'] = date('Y-m-d H:i:s',$order['upload_finish_at']);
            $order['shipping_time'] = date('Y-m-d H:i:s',$order['shipping_at']);
            $order['confirm_time'] = date('Y-m-d H:i:s',$order['confirm_at']);
            
            return new Response(Response::CODE_COMMON_OK, null, $order);
        } else {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => \Yii::t('app', 'Order')]);
        }
    }

}
