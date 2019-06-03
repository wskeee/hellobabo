<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\order\Order;
use common\models\order\OrderGoodsScene;
use common\models\User;
use Yii;

/**
 * 初始绘本准备
 * 返回绘本素材和场景
 */
class UploadImageReady extends BaseAction
{

    public function run()
    {
        $order_id = $this->getSecretParam('order_id');

        /* @var $user User */
        $user = Yii::$app->user->identity;
        $order = Order::findOne(['id' => $order_id]);
        if (!$order) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => Yii::t('app', 'Order')]);
        }
        $scenes = OrderGoodsScene::find()->where(['order_id' => $order_id, 'is_del' => 0])->orderBy(['sort_order' => SORT_ASC])->all();

        return new Response(Response::CODE_COMMON_OK, null, [
            'order' => $order,
            'scenes' => $scenes,
        ]);
    }

}
