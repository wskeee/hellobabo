<?php


namespace apiend\modules\v1\actions\coupon;


use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\order\Coupon;
use common\models\order\UserCoupon;
use yii\db\Exception;

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

        if($coupon->quota <= $coupon->take_count){
            // 卷已被领光
            return new Response(Response::CODE_ORDER_COUPON_RECEIVE_FAIL, '出手晚啦,优惠卷被人枪光了！');
        }

        // 检查重复领取
        $receive_count = UserCoupon::find()->where(['user_id' => $user_id, 'coupon_id' => $coupon_id])->count();
        if ($receive_count && $coupon->user_max_count <= $receive_count) {
            return new Response(Response::CODE_ORDER_COUPON_RECEIVE_FAIL, '领取数量超出限制');
        }

        // 检查是否过期
        $time = time();
        if ($coupon->getOldAttribute('start_time') > $time) {
            return new Response(Response::CODE_ORDER_COUPON_RECEIVE_FAIL, '优惠卷未上架', $coupon->start_time);
        } else if ($coupon->getOldAttribute('end_time') < $time) {
            return new Response(Response::CODE_ORDER_COUPON_RECEIVE_FAIL, '优惠卷已下架', $coupon->end_time);
        }

        // 领取
        $isAbsolute = $coupon->valid_type == Coupon::VALID_TYPE_ABSOLUTE;
        $valid_start_time = $isAbsolute ? $coupon->getOldAttribute('valid_start_time') : $time;
        $valid_end_time = $isAbsolute ? $coupon->getOldAttribute('valid_end_time') : strtotime("today +{$coupon->valid_days} 23:59:59");

        $user_coupon = new UserCoupon([
            'user_id' => $user_id,
            'coupon_id' => $coupon_id,
            'valid_start_time' => $valid_start_time,
            'valid_end_time' => $valid_end_time,
            'status' => UserCoupon::STATUS_UNUSED,
        ]);
        $user_coupon->loadDefaultValues();

        $tran = \Yii::$app->db->beginTransaction();
        if ($user_coupon->validate() && $user_coupon->save()) {
            // 增加领卷数
            $coupon->take_count++;
            try{
                $coupon->save();
                $tran->commit();
            }catch (Exception $e){
                $tran->rollBack();
                return new Response(Response::CODE_ORDER_COUPON_RECEIVE_FAIL, null, $e);
            }
            return new Response(Response::CODE_COMMON_OK, null, $user_coupon);
        } else {
            $tran->rollBack();
            return new Response(Response::CODE_ORDER_COUPON_RECEIVE_FAIL, null, $user_coupon->getErrorSummary(true));
        }
    }
}