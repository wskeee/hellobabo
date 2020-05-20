<?php

namespace common\services;

use common\models\order\Coupon;
use common\models\order\UserCoupon;
use common\models\User;
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

    /**
     * 获取当前优惠最大的优惠卷
     * @param int $user_id
     * @param int $goods_id
     * @param float $amount 订单总价
     * @return UserCoupon
     */
    public static function getCouponBest($user_id, $goods_id, $amount)
    {
        $user_coupons = self::getUseAbleCoupon($user_id, $goods_id);
        return self::getCouponBaseByCoupons($user_coupons, $amount);
    }

    /**
     * 获取当前优惠最大的优惠卷
     * @param array $user_coupons
     * @return UserCoupon
     */
    public static function getCouponBaseByCoupons($user_coupons, $amount)
    {
        $max_value = 0;
        $base_coupon = null;
        foreach ($user_coupons as $user_coupon) {
            /** @var UserCoupon $user_coupon */
            $value = $user_coupon->coupon->used_amount < 1 ? $user_coupon->coupon->used_amount * $amount : $user_coupon->coupon->used_amount;
            if (!$base_coupon || $value >= $max_value) {
                $max_value = $value;
                $base_coupon = $user_coupon;
            }
        }
        return $base_coupon;
    }

    /**
     * 获取可用优惠卷
     * @param int $user_id
     * @param int $goods_id
     * @return array
     */
    public static function getUseAbleCoupon($user_id, $goods_id = null)
    {
        $time = time();
        $query = UserCoupon::find()
            ->where([
                'user_id' => $user_id,
                'status' => UserCoupon::STATUS_UNUSED,
            ])
            ->andWhere(['<=', 'valid_start_time', $time])
            ->andWhere(['>=', 'valid_end_time', $time])
            ->with('coupon');
        if ($goods_id) {
            $query->andWhere(['or',
                // 新手和平台卷无需其它条件
                ['used' => [Coupon::USED_NEWER, Coupon::USED_PLATFORM]],
                // 商品类型卷，类型匹配
                //['used' => Coupon::USED_TYPE, 'with_id' => $goods_id],
                // 商品卷
                ['used' => Coupon::USED_GOODS, 'with_id' => $goods_id],
            ]);
        }

        $user_coupons = $query->all();
        return $user_coupons;
    }


    /**
     * 获取未用优惠卷
     * @param int $user_id
     */
    public static function getUnuseCoupon($user_id)
    {
        $query = UserCoupon::find()
            ->where([
                'user_id' => $user_id,
                'status' => UserCoupon::STATUS_UNUSED
            ]);
        return $query->all();
    }

    /**
     * 获取已经使用过的优惠卷
     * @param int $user_id
     * @return array
     */
    public static function getHasUsedCoupon($user_id)
    {
        $query = UserCoupon::find()
            ->where([
                'user_id' => $user_id,
                'status' => UserCoupon::STATUS_USED
            ]);
        return $query->all();
    }

    /**
     * 获取已过期优惠卷
     * @param int $user_id
     * @return array
     */
    public static function getExpireCoupon($user_id)
    {
        $query = UserCoupon::find()
            ->where([
                'user_id' => $user_id,
                'status' => UserCoupon::STATUS_UNUSED,
            ])
            ->andWhere(['>=', 'valid_end_time', time()]);
        return $query->all();
    }


}