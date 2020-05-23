<?php


namespace apiend\modules\v1\actions\coupon;


use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\services\CouponService;
use Yii;

class GetCouponBest extends BaseAction
{
    protected $requiredParams = ['goods_id', 'amount'];

    public function run()
    {
        $user = Yii::$app->user->identity;
        $goods_id = $this->getSecretParam('goods_id');
        $amount = $this->getSecretParam('amount');

        $coupon = CouponService::getCouponBest($user->id, $goods_id, $amount);
        return new Response(Response::CODE_COMMON_OK, null, $coupon);
    }
}