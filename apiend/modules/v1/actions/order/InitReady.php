<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\goods\GoodsMaterial;
use common\models\order\Order;
use common\models\order\OrderGoodsMaterial;
use common\models\order\OrderGoodsScene;
use common\models\User;
use Yii;

/**
 * 初始绘本准备
 * 返回绘本素材和场景
 */
class InitReady extends BaseAction
{
/* 必须参数 */

    protected $requiredParams = ['order_sn'];
    
    public function run()
    {
        /* @var $user User */
        $user = Yii::$app->user->identity;
        
        //订单
        $order = Order::findOne(['order_sn' => $this->getSecretParam('order_sn'), 'created_by' => $user->id]);
        //所有绘本素材
        $goodsMaterials = GoodsMaterial::find()->where(['goods_id' => $order->goods_id, 'is_del'])->all();
        //已经选择的素材
        $orderGoodsMaterials = OrderGoodsMaterial::find()->where(['order_id' => $order->id, 'is_del' => 0])->all();
        //已经选择的场景
        $orderGoodsScenes = OrderGoodsScene::find()->where(['order_id' => $order->id, 'is_del' => 0])->all();


        return new Response(Response::CODE_COMMON_OK, null, [
            'order' => $order,
            'goodsMaterials' => $goodsMaterials,
            'orderGoodsMaterials' => $orderGoodsMaterials,
            'orderGoodsScenes' => $orderGoodsScenes,
        ]);
    }

}
