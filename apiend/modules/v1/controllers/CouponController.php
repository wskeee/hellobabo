<?php

namespace apiend\modules\v1\controllers;

use apiend\controllers\ApiController;
use apiend\modules\v1\actions\coupon\ExchangeCoupon;
use apiend\modules\v1\actions\coupon\GetChooseList;
use apiend\modules\v1\actions\coupon\GetCouponBest;
use apiend\modules\v1\actions\coupon\GetCouponList;
use apiend\modules\v1\actions\coupon\GetMyCouponList;
use apiend\modules\v1\actions\coupon\ReceiveCoupon;
use apiend\modules\v1\actions\coupon\SearchCouponByCode;
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
class CouponController extends ApiController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['optional'] = [
        ];
        $behaviors['verbs']['actions'] = [
            'get-coupon-list' => ['get'],
            'get-coupon-best' => ['get'],
            'get-choose-list' => ['get'],
            'get-my-coupon-list' => ['get'],
            'exchange-coupon' => ['post'],
            'receive-coupon' => ['post'],
            'search-coupon-by-code' => ['post'],
        ];
        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'get-coupon-list' => ['class' => GetCouponList::class],
            'get-coupon-best' => ['class' => GetCouponBest::class],
            'get-choose-list' => ['class' => GetChooseList::class],
            'get-my-coupon-list' => ['class' => GetMyCouponList::class],
            'exchange-coupon' => ['class' => ExchangeCoupon::class],
            'receive-coupon' => ['class' => ReceiveCoupon::class],
            'search-coupon-by-code' => ['class' => SearchCouponByCode::class],
        ];
    }

}
