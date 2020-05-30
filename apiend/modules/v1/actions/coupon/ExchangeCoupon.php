<?php


namespace apiend\modules\v1\actions\coupon;


use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\services\CouponService;

class ExchangeCoupon extends BaseAction
{
    protected $requiredParams = ['code'];

    public function run()
    {
        $user_id = \Yii::$app->user->id;
        $code = $this->getSecretParam('code');
        $res = CouponService::exchange($code, $user_id);

        if ($res['result']) {
            return new Response(Response::CODE_COMMON_OK, $res['msg'], $res['data']);
        } else {
            return new Response(Response::CODE_ORDER_COUPON_RECEIVE_FAIL, $res['msg'], $res['data']);
        }
    }
}