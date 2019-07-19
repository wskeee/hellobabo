<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\order\Order;
use Yii;

/**
 * 确认订单
 *
 * @author Administrator
 */
class ConfirmOrder extends BaseAction
{

    protected $requiredParams = ['order_id'];

    public function run()
    {
        $order_id = $this->getSecretParam('order_id', null);
        $order = Order::findOne(['id' => $order_id]);
        if($order == null){
            return new Response(Response::CODE_COMMON_NOT_FOUND,null,null,['param' => Yii::t('app', 'Order')]);
        }
        
        $result = $order->finish();
        if($result === true){
            return new Response(Response::CODE_COMMON_OK);
        }else{
            return new Response(Response::CODE_COMMON_SAVE_DB_FAIL,$result);
        }
    }

}
