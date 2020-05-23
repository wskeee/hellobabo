<?php

namespace common\services;

use common\models\goods\Goods;
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
        $user_coupons = self::getUseAbleCoupon($user_id, $goods_id, $amount);
        return self::getCouponBestByCoupons($user_coupons, $amount);
    }

    /**
     * 获取当前优惠最大的优惠卷
     * @param array $user_coupons
     * @return UserCoupon
     */
    public static function getCouponBestByCoupons($user_coupons, $amount)
    {
        if (empty($user_coupons) || $amount <= 0) return null;
        $max_value = 0;
        $base_coupon = null;
        foreach ($user_coupons as $user_coupon) {
            $value = $user_coupon['used_amount'] < 1 ? (1 - $user_coupon['used_amount']) * $amount : $user_coupon['used_amount'];
            if (!$base_coupon || $value > $max_value) {
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
     * @param int $amount 商品金额
     * @return array
     */
    public static function getUseAbleCoupon($user_id, $goods_id = null, $amount = 0)
    {
        $time = time();
        $query = UserCoupon::find()
            ->alias('user_coupon')
            ->select([
                'user_coupon.*',
                'coupon.title',
                'coupon.with_id',
                'coupon.used',
                'coupon.with_amount',
                'coupon.used_amount',
            ])
            ->leftJoin(['coupon' => Coupon::tableName()], 'coupon.id = user_coupon.coupon_id')
            ->where([
                'user_coupon.user_id' => $user_id,
                'user_coupon.status' => UserCoupon::STATUS_UNUSED,
            ])
            ->andWhere(['<=', 'user_coupon.valid_start_time', $time])
            ->andWhere(['>=', 'user_coupon.valid_end_time', $time]);
        if ($goods_id) {
            $query->andWhere(['or',
                // 新手和平台卷无需其它条件
                ['coupon.used' => [Coupon::USED_NEWER, Coupon::USED_PLATFORM]],
                // 商品类型卷，类型匹配
                //['used' => Coupon::USED_TYPE, 'with_id' => $goods_id],
                // 商品卷
                ['coupon.used' => Coupon::USED_GOODS, 'with_id' => $goods_id],
            ]);
        }
        // 当传了金额时，检查满减配置
        if ($amount > 0) {
            $query->andWhere(['or',
                ['coupon.type' => Coupon::TYPE_NO_THRESHOLD],
                ['and', ['coupon.type' => Coupon::TYPE_FULL], ['>=', 'coupon.with_amount', $amount]]]);
        }

        $user_coupons = $query->asArray()->all();
        // 值转换
        foreach ($user_coupons as &$item) {
            $item = self::appendCoupon($item);
        }
        return $user_coupons ? $user_coupons : [];
    }


    /**
     * 获取未用优惠卷
     * @param int $user_id
     */
    public static function getUnuseCoupon($user_id)
    {
        $query = UserCoupon::find()
            ->alias('user_coupon')
            ->leftJoin(['coupon' => Coupon::tableName()], 'coupon.id = user_coupon.coupon_id')
            ->select([
                'user_coupon.*',
                'coupon.title',
                'coupon.with_id',
                'coupon.used',
                'coupon.with_amount',
                'coupon.used_amount',
            ])
            ->where([
                'user_coupon.user_id' => $user_id,
                'user_coupon.status' => UserCoupon::STATUS_UNUSED
            ]);
        $result = $query->asArray()->all();
        // 值转换
        foreach ($result as &$item) {
            $item = self::appendCoupon($item);
        }
        return $result;
    }

    /**
     * 附加其它属性到优惠卷
     * @param array $coupon
     * @return array
     */
    private static function appendCoupon($item)
    {
        $item['valid_start_time_text'] = date('Y-m-d', $item['valid_start_time']);
        $item['valid_end_time_text'] = date('Y-m-d', $item['valid_end_time']);

        // 绘本优惠卷
        if (($item['used'] == Coupon::USED_GOODS || $item['used'] == Coupon::USED_CODE) && !empty($item['with_id'])) {
            $goods = Goods::findOne(['id' => $item['with_id']]);
            if ($goods) {
                $item['used_text'] = Coupon::$usedNames[$item['used']] . "({$goods->goods_name})";
            } else {
                $item['used_text'] = Coupon::$usedNames[$item['used']];
            }
        } else {
            $item['used_text'] = Coupon::$usedNames[$item['used']];
        }
        return $item;
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