<?php

namespace apiend\modules\v1\controllers;

use apiend\controllers\ApiController;
use apiend\modules\v1\actions\order\CreateOrder;
use apiend\modules\v1\actions\order\GetOrderDetail;
use apiend\modules\v1\actions\order\GetOrderList;
use apiend\modules\v1\actions\order\OrderReady;

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
            'get-list' => ['class' => GetOrderList::class],
            'get-detail' => ['class' => GetOrderDetail::class],
            'ready' => ['class' => OrderReady::class],
        ];
    }

}
