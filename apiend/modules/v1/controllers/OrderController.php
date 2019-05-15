<?php

namespace apiend\modules\v1\controllers;

use apiend\controllers\ApiController;
use apiend\modules\v1\actions\order\ChangeGoods;
use apiend\modules\v1\actions\order\ChangePassword;
use apiend\modules\v1\actions\order\CreateOrder;
use apiend\modules\v1\actions\order\GetOrderDetail;
use apiend\modules\v1\actions\order\GetOrderList;
use apiend\modules\v1\actions\order\Ready;

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
            'create' => ['post'],
            'get-list' => ['get'],
            'get-order-detail' => ['get'],
            'ready' => ['get'],
            'change-pwd' => ['post'],
            'change-goods' => ['post'],
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
            'get-order-detail' => ['class' => GetOrderDetail::class],
            'ready' => ['class' => Ready::class],
            'change-pwd' => ['class' => ChangePassword::class],
            'change-goods' => ['class' => ChangeGoods::class],
        ];
    }

}
