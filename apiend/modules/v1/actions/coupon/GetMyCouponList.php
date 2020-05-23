<?php


namespace apiend\modules\v1\actions\coupon;


use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\services\CouponService;
use Yii;

class GetMyCouponList extends BaseAction
{
    public function run()
    {
        $user = Yii::$app->user->identity;
        // 可用的
        $useable = CouponService::getUseAbleCoupon($user->id);
        // 不可用的
        $unuseable = CouponService::getUnableUseCoupon($user->id, $useable);
        // 使用记录
        $usehistory = CouponService::getHasUsedCoupon($user->id);

        $data = [
            'useable' => $useable,
            'unuseable' => $unuseable,
            'usehistory' => $usehistory,
        ];
        return new Response(Response::CODE_COMMON_OK, null, $data);
    }
}