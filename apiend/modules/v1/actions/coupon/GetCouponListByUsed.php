<?php


namespace apiend\modules\v1\actions\coupon;


use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\services\CouponService;

class GetCouponListByUsed extends BaseAction
{
    protected $requiredParams = ['used'];

    public function run()
    {
        $user_id = \Yii::$app->user->id;
        $used = $this->getSecretParam('used');
        $list = CouponService::getUseableCouponByUsed($user_id, $used);
        return new Response(Response::CODE_COMMON_OK, null, $list);
    }
}