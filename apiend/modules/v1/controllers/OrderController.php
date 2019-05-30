<?php

namespace apiend\modules\v1\controllers;

use apiend\controllers\ApiController;
use apiend\modules\v1\actions\order\CheckPay;
use apiend\modules\v1\actions\order\CreateOrder;
use apiend\modules\v1\actions\order\GetOrderDetail;
use apiend\modules\v1\actions\order\GetOrderList;
use apiend\modules\v1\actions\order\Init;
use apiend\modules\v1\actions\order\InitReady;
use apiend\modules\v1\actions\order\OrderReady;
use apiend\modules\v1\actions\order\Pay;
use apiend\modules\v1\actions\order\SaveGoodsMaterial;

/**
 * 套餐
 *
 * @author Administrator
 */
class OrderController extends ApiController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['optional'] = [
        ];
        $behaviors['verbs']['actions'] = [
            'create-order' => ['post'],
            'pay' => ['post'],
            'check-pay' => ['get'],
            'init-ready' => ['get'],
            'init' => ['post'],
            'save-goods-material' => ['post'],
            'get-list' => ['get'],
            'get-order-detail' => ['get'],
            'ready' => ['get'],
        ];
        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'create' => ['class' => CreateOrder::class],
            'pay' => ['class' => Pay::class],
            'check-pay' => ['class' => CheckPay::class],
            'init-ready' => ['class' => InitReady::class],
            'init' => ['class' => Init::class],
            'save-goods-material' => ['class' => SaveGoodsMaterial::class],
            'get-list' => ['class' => GetOrderList::class],
            'get-detail' => ['class' => GetOrderDetail::class],
            'ready' => ['class' => OrderReady::class],
        ];
    }

}