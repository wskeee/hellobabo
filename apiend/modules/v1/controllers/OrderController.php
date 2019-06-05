<?php

namespace apiend\modules\v1\controllers;

use apiend\controllers\ApiController;
use apiend\modules\v1\actions\order\CheckPay;
use apiend\modules\v1\actions\order\ClearSceneUserImage;
use apiend\modules\v1\actions\order\CreateOrder;
use apiend\modules\v1\actions\order\GetOrderDetail;
use apiend\modules\v1\actions\order\GetOrderList;
use apiend\modules\v1\actions\order\Init;
use apiend\modules\v1\actions\order\InitReady;
use apiend\modules\v1\actions\order\OrderGoodsSceneReady;
use apiend\modules\v1\actions\order\OrderReady;
use apiend\modules\v1\actions\order\Pay;
use apiend\modules\v1\actions\order\SaveOrderGoodsMaterial;
use apiend\modules\v1\actions\order\SaveOrderGoodsScene;
use apiend\modules\v1\actions\order\SaveSceneUserImage;
use apiend\modules\v1\actions\order\UploadImageCompleted;
use apiend\modules\v1\actions\order\UploadImageReady;

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
            'save-order-goods-material' => ['post'],
            'order-goods-scene-ready' => ['get'],
            'save-order-goods-scene' => ['post'],
            'order-upload-img-ready' => ['get'],
            'order-upload-img-submit' => ['post'],
            'save-scene-user-img' => ['post'],
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
            'save-order-goods-material' => ['class' => SaveOrderGoodsMaterial::class],
            'order-goods-scene-ready' => ['class' => OrderGoodsSceneReady::class],
            'save-order-goods-scene' => ['class' => SaveOrderGoodsScene::class],
            'order-upload-img-ready' => ['class' => UploadImageReady::class],
            'order-upload-img-submit' => ['class' => UploadImageCompleted::class],
            'save-scene-user-img' => ['class' => SaveSceneUserImage::class],
            'clear-scene-user-img' => ['class' => ClearSceneUserImage::class],
            'get-list' => ['class' => GetOrderList::class],
            'get-detail' => ['class' => GetOrderDetail::class],
            'ready' => ['class' => OrderReady::class],
        ];
    }

}
