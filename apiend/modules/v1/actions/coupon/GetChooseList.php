<?php


namespace apiend\modules\v1\actions\coupon;


use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\services\CouponService;
use Yii;

class GetChooseList extends BaseAction
{
    protected $requiredParams = ['goods_id', 'amount'];

    public function run()
    {
        $user = Yii::$app->user->identity;
        $goods_id = $this->getSecretParam('goods_id');
        $amount = $this->getSecretParam('amount');
        // 可用的
        $useable = CouponService::getUseAbleCoupon($user->id, $goods_id, $amount);
        // 不可用的
        $unuseable = CouponService::getUnableUseCoupon($user->id, $useable);

        $data = [
            'coupon' => CouponService::getCouponBestByCoupons($useable, $amount),
            'useable' => $useable,
            'unuseable' => $unuseable,
        ];
        return new Response(Response::CODE_COMMON_OK, null, $data);
    }
}