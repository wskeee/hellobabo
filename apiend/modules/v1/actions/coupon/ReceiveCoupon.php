<?php


namespace apiend\modules\v1\actions\coupon;


use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\order\Coupon;
use common\models\order\UserCoupon;
use common\services\CouponService;
use yii\db\Exception;

class ReceiveCoupon extends BaseAction
{
    protected $requiredParams = ['coupon_id'];

    public function run()
    {
        $user_id = \Yii::$app->user->id;
        $coupon_id = $this->getSecretParam('coupon_id');

        $res = CouponService::receive($coupon_id, $user_id);
        if ($res['result']) {
            return new Response(Response::CODE_COMMON_OK, $res['msg'], $res['data']);
        } else {
            return new Response(Response::CODE_ORDER_COUPON_RECEIVE_FAIL, $res['msg'], $res['data']);
        }
    }
}