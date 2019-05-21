<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\platform\Order;
use Yii;

class GetOrderDetail extends BaseAction
{
    /* 必须参数 */

    protected $requiredParams = ['order_sn'];
    
    public function run()
    {
        $order_sn = $this->getSecretParam('order_sn', null);

        $order = Order::findOne(['order_sn' => $order_sn, 'created_by' => Yii::$app->user->id]);
        
        if($order){
            return new Response(Response::CODE_COMMON_OK, null, $order);
        }else{
            return new Response(Response::CODE_COMMON_NOT_FOUND,null,null,['param' => \Yii::t('app', 'Order')]);
        }
    }
}
