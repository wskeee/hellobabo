<?php

namespace apiend\modules\v1\actions\platform;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\order\Order;
use common\models\platform\Issue;
use Yii;

/**
 * 添加反馈
 */
class AddIssue extends BaseAction
{
    /* 必须参数 */

    protected $requiredParams = ['type'];

    public function run()
    {
        $type = $this->getSecretParam('type');
        $order_id = $this->getSecretParam('order_id');
        $model = new Issue();
        $model->created_by = Yii::$app->user->id;
        $model->load($this->getSecretParams(), "");
        if($order_id){
            $order = Order::findOne(['id' => $this->getSecretParam('order_id')]);
            $model->order_sn = $order->order_sn;
            $model->goods_id = $order->goods_id;
        }
        
        if ($model->validate() && $model->save()) {
            return new Response(Response::CODE_COMMON_OK);
        } else {
            return new Response(Response::CODE_COMMON_SAVE_DB_FAIL, null, $model->getErrorSummary(true));
        }
    }

}
