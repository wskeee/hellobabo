<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\order\Order;
use common\models\order\OrderGoods;
use common\models\system\Region;
use Yii;

class GetOrderDetail extends BaseAction
{
    /* 必须参数 */

    protected $requiredParams = ['order_id'];

    public function run()
    {
        $order_id = $this->getSecretParam('order_id', null);

        $model = Order::findOne(['id' => $order_id, 'created_by' => Yii::$app->user->id]);

        if ($model) {
            $order = $model->toArray();
            $order['address'] = implode("", Region::getRegionName([$order['province'],$order['city'],$order['district'],])).$order['address'];
            $order['created_time'] = date('Y-m-d H:i:s',$order['created_at']);
            
            $goods_list = OrderGoods::find()->where(['order_id' => $order_id, 'is_del' => 0])->all();

            return new Response(Response::CODE_COMMON_OK, null, [
                'order' => $order,
                'goods_list' => $goods_list,
            ]);
        } else {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => \Yii::t('app', 'Order')]);
        }
    }

}
