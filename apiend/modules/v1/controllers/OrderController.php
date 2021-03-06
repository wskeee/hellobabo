<?php

namespace apiend\modules\v1\controllers;

use apiend\controllers\ApiController;
use apiend\modules\v1\actions\coupon\GetCouponList;
use apiend\modules\v1\actions\order\CancelOrder;
use apiend\modules\v1\actions\order\CheckPay;
use apiend\modules\v1\actions\order\CheckPose;
use apiend\modules\v1\actions\order\ClearSceneUserImage;
use apiend\modules\v1\actions\order\ConfirmOrder;
use apiend\modules\v1\actions\order\CreateGroupon;
use apiend\modules\v1\actions\order\CreateOrder;
use apiend\modules\v1\actions\order\EndGrouponInvite;
use apiend\modules\v1\actions\order\GetGrouponDetail;
use apiend\modules\v1\actions\order\GetMyOrderGoods;
use apiend\modules\v1\actions\order\GetOrderDetail;
use apiend\modules\v1\actions\order\GetOrderGoodsDetail;
use apiend\modules\v1\actions\order\GetOrderList;
use apiend\modules\v1\actions\order\GetTempOrder;
use apiend\modules\v1\actions\order\Init;
use apiend\modules\v1\actions\order\InitReady;
use apiend\modules\v1\actions\order\JoinGrouponReady;
use apiend\modules\v1\actions\order\OrderGoodsSceneReady;
use apiend\modules\v1\actions\order\OrderReady;
use apiend\modules\v1\actions\order\Pay;
use apiend\modules\v1\actions\order\PresentInitReady;
use apiend\modules\v1\actions\order\PresentSaveOrderGoodsScene;
use apiend\modules\v1\actions\order\SaveOrderGoodsMaterial;
use apiend\modules\v1\actions\order\SaveOrderGoodsScene;
use apiend\modules\v1\actions\order\SaveSceneUserImage;
use apiend\modules\v1\actions\order\SaveTempOrder;
use apiend\modules\v1\actions\order\TempOrderReady;
use apiend\modules\v1\actions\order\TempOrderSceneReady;
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
            'ready' => ['get'],
            'create-order' => ['post'],
            'pay' => ['post'],
            'check-pay' => ['get'],
            'init-ready' => ['get'],
            'present-init-ready' => ['get'],
            'init' => ['post'],
            'save-order-goods-material' => ['post'],
            'order-goods-scene-ready' => ['get'],
            'save-order-goods-scene' => ['post'],
            'present-save-ogs' => ['post'],
            'order-upload-img-ready' => ['get'],
            'order-upload-img-submit' => ['post'],
            'save-scene-user-img' => ['post'],
            'get-list' => ['get'],
            'get-detail' => ['get'],
            'cancel' => ['post'],
            'confirm' => ['post'],
            'temp-order-ready' => ['get'],
            'get-temp-order' => ['get'],
            'save-temp-order' => ['post'],
            'temp-order-scene-ready' => ['get'],
            // 订单商品
            'get-my-order-goods' => ['get'],
            'get-order-goods-detail' => ['get'],
            // 团购
            'create-groupon' => ['post'],
            'get-groupon-detail' => ['get'],
            'join-groupon-ready' => ['get'],
            'end-groupon-invite' => ['post'],
            // 优惠卷
            'get-coupon-list' => ['get'],
        ];
        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'ready' => ['class' => OrderReady::class],
            'create' => ['class' => CreateOrder::class],
            'pay' => ['class' => Pay::class],
            'check-pay' => ['class' => CheckPay::class],
            'init-ready' => ['class' => InitReady::class],
            'present-init-ready' => ['class' => PresentInitReady::class],
            'init' => ['class' => Init::class],
            'save-order-goods-material' => ['class' => SaveOrderGoodsMaterial::class],
            'order-goods-scene-ready' => ['class' => OrderGoodsSceneReady::class],
            'save-order-goods-scene' => ['class' => SaveOrderGoodsScene::class],
            'present-save-ogs' => ['class' => PresentSaveOrderGoodsScene::class],
            'order-upload-img-ready' => ['class' => UploadImageReady::class],
            'order-upload-img-submit' => ['class' => UploadImageCompleted::class],
            'save-scene-user-img' => ['class' => SaveSceneUserImage::class],
            'clear-scene-user-img' => ['class' => ClearSceneUserImage::class],
            'get-list' => ['class' => GetOrderList::class],
            'get-detail' => ['class' => GetOrderDetail::class],
            'cancel' => ['class' => CancelOrder::class],
            'confirm' => ['class' => ConfirmOrder::class],
            // 获取初始临时订单
            'temp-order-ready' => ['class' => TempOrderReady::class],
            // 获取临时订单
            'get-temp-order' => ['class' => GetTempOrder::class],
            // 获取临时订单场景配置
            'temp-order-scene-ready' => ['class' => TempOrderSceneReady::class],
            // 保存临时订单
            'save-temp-order' => ['class' => SaveTempOrder::class],
            // 订单商品
            'get-my-order-goods' => ['class' => GetMyOrderGoods::class],
            'get-order-goods-detail' => ['class' => GetOrderGoodsDetail::class],
            // 团购
            'create-groupon' => ['class' => CreateGroupon::class],
            'get-groupon-detail' => ['class' => GetGrouponDetail::class],
            'join-groupon-ready' => ['class' => JoinGrouponReady::class],
            'end-groupon-invite' => ['class' => EndGrouponInvite::class],
            // 优惠卷
            'get-coupon-list' => ['class' => GetCouponList::class],
        ];
    }

}
