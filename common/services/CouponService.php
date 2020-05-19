<?php
namespace common\services;

use common\models\order\UserCoupon;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class CouponService
{
    /**
     * 获取用户优惠卷的数量
     * @param int $user_id
     * @param array|int $coupon_id
     * @return array [coupon_id => count]
     */
    public static function getUserCouponCount($user_id, $coupon_id)
    {
        $query = (new Query())
            ->select(['COUNT(coupon_id) as count', 'coupon_id'])
            ->from([UserCoupon::tableName()])
            ->where([
                'user_id' => $user_id,
                'coupon_id' => $coupon_id
            ])
            ->groupBy('coupon_id');
        $result = $query->all();
        return ArrayHelper::map($result, 'coupon_id', 'count');
    }
}