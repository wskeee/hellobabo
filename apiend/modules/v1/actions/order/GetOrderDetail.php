<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\platform\Order;
use Yii;

class GetOrderDetail extends BaseAction
{

    public function run()
    {
        if (!$this->verify()) {
            return $this->verifyError;
        }
        
        /* 检查必须参数 */
        if ($notfounds = $this->checkRequiredParams($this->getSecretParams(), ['order_sn'])) {
            return new Response(Response::CODE_COMMON_MISS_PARAM, null, null, ['param' => implode(',', $notfounds)]);
        }

        $order_sn = $this->getSecretParam('order_sn', null);

        $order = Order::findOne(['order_sn' => $order_sn, 'created_by' => Yii::$app->user->id]);
        
        if($order){
            return new Response(Response::CODE_COMMON_OK, null, $order);
        }else{
            return new Response(Response::CODE_COMMON_NOT_FOUND,null,null,['param' => \Yii::t('app', 'Order')]);
        }
    }
}
