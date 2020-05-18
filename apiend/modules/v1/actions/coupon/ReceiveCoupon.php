<?php


namespace apiend\modules\v1\actions\coupon;


use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\order\Coupon;
use common\models\order\UserCoupon;

class ReceiveCoupon extends BaseAction
{
    protected $requiredParams = ['coupon_id'];

    public function run()
    {
        $user_id = \Yii::$app->user->id;
        $coupon_id = $this->getSecretParam('coupon_id');

        $coupon = Coupon::findOne(['id' => $coupon_id, 'status' => Coupon::STATUS_PUBLISHED]);
        if (!$coupon) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => '优惠卷']);
        }

        // 检查重复领取
        $receive_count = UserCoupon::find()->where(['user_id' => $user_id, 'coupon_id' => $coupon_id])->count();
        if ($receive_count && $coupon->user_max_count <= $receive_count) {
            return new Response(Response::CODE_ORDER_COUPON_RECEIVE_FAIL, '领取数量超出限制');
        }

        // 检查是否过期
        $time = time();
        if ($coupon->start_time > $time || $coupon->end_time < $time) {
            return new Response(Response::CODE_ORDER_COUPON_RECEIVE_FAIL, '优惠卷已下架');
        }

        // 领取
        $daySecond = 60 * 60 * 24;
        $isAbsolute = $coupon->valid_type == Coupon::VALID_TYPE_ABSOLUTE;
        $valid_start_time = $isAbsolute ? $coupon->valid_start_time : $time;
        $valid_end_time = $isAbsolute ? $coupon->valid_end_time : $time + $coupon->valid_days * $daySecond;

        $user_coupon = new UserCoupon([
            'user_id' => $user_id,
            'coupon_id' => $coupon_id,
            'valid_start_time' => $valid_start_time,
            'valid_end_time' => $valid_end_time,
            'status' => UserCoupon::STATUS_UNUSED,
        ]);
        $user_coupon->loadDefaultValues();

        if ($user_coupon->validate() && $user_coupon->save()) {
            return new Response(Response::CODE_COMMON_OK, null, $user_coupon);
        }else{
            return new Response(Response::CODE_ORDER_COUPON_RECEIVE_FAIL, null, $user_coupon->getErrorSummary(true));
        }
    }
}