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
     * 检查优惠卷是否能用
     * @param int $coupon_id
     * @param int $goods_id
     * @param int $amount 订单原价/商品总价
     *
     * @return array
     */
    public static function checkCouponCanUse($coupon_id, $goods_id, $amount)
    {
        if (empty($coupon_id) || !($coupon = UserCoupon::findOne(['id' => $coupon_id]))) {
            return ['result' => 0, 'msg' => '找不到优惠卷', 'data' => null];
        }

        // 检查原优惠卷状态
        if ($coupon->coupon->status != Coupon::STATUS_PUBLISHED) {
            return ['result' => 0, 'msg' => '优惠卷' . Coupon::$statusNames[$coupon->coupon->status], 'data' => null];
        }

        // 检查优惠卷使用状态
        if ($coupon->status == UserCoupon::STATUS_USED) {
            return ['result' => 0, 'msg' => '优惠卷已使用', 'data' => null];
        } else if ($coupon->status == UserCoupon::STATUS_TIMEOUT) {
            return ['result' => 0, 'msg' => '优惠卷已过期', 'data' => null];
        }

        // 检查时间
        $time = time();
        if ($coupon->valid_start_time > $time) {
            return ['result' => 0, 'msg' => '优惠卷未开始', 'data' => null];
        } else if ($coupon->valid_end_time < $time) {
            return ['result' => 0, 'msg' => '优惠卷已过期', 'data' => null];
        }

        // 检查类型
        if (!empty($coupon->coupon->with_id) && $coupon->coupon->with_id != $goods_id) {
            return ['result' => 0, 'msg' => '优惠卷仅限专属绘本使用', 'data' => null];
        }

        // 满减
        if ($coupon->coupon->type == Coupon::TYPE_FULL && $coupon->coupon->with_amount > $amount) {
            return ['result' => 0, 'msg' => '优惠卷不满足最低金额限制', 'data' => ['with_amount' => $coupon->coupon->with_amount, 'order_amount' => $amount]];
        }

        return ['result' => 1, 'msg' => 'OK', 'data' => $coupon];
    }

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
        $query = self::getQuery()
            // 查询属于我的并且未用
            ->where([
                'user_coupon.user_id' => $user_id,
                'user_coupon.status' => UserCoupon::STATUS_UNUSED,
            ])
            // 已发布的优惠卷
            ->andWhere(['coupon.status' => Coupon::STATUS_PUBLISHED])
            // 当前可用未过期
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
                ['and', ['coupon.type' => Coupon::TYPE_FULL], ['<=', 'coupon.with_amount', $amount]]]);
        }

        $user_coupons = $query->all();
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
        $query = self::getQuery()
            ->where([
                'user_coupon.user_id' => $user_id,
                'user_coupon.status' => UserCoupon::STATUS_UNUSED
            ]);
        $result = $query->all();
        // 值转换
        foreach ($result as &$item) {
            $item = self::appendCoupon($item);
        }
        return $result;
    }

    /**
     * 获取不可用优惠卷
     *
     * @param int $user_id
     * @param array $useable
     * @return array
     */
    public static function getUnableUseCoupon($user_id, $useable = null)
    {
        if (empty($useable)) {
            $useable = self::getUseAbleCoupon($user_id);
        }
        $useable_map = ArrayHelper::map($useable, 'id', 'id');
        // 未用的
        $unuse = self::getUnuseCoupon($user_id);
        // 不可用的
        $unuseable = [];
        foreach ($unuse as $i => $item) {
            if (!isset($useable_map[$item['id']])) {
                $unuseable[] = $item;
            }
        }
        return $unuseable;
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
        $query = self::getQuery()
            ->where([
                'user_coupon.user_id' => $user_id,
                'user_coupon.status' => UserCoupon::STATUS_USED
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
        $query = self::getQuery()
            ->where([
                'user_coupon.user_id' => $user_id,
                'user_coupon.status' => UserCoupon::STATUS_UNUSED,
            ])
            ->andWhere(['<', 'user_coupon.valid_end_time', time()]);
        return $query->all();
    }

    //-------------------------------------------------------------------------------
    //
    // util
    //
    //-------------------------------------------------------------------------------
    /**
     * 公共查询条件
     * @return Query
     */
    private static function getQuery()
    {
        $query = new Query();
        $query->from(['user_coupon' => UserCoupon::tableName()])
            ->leftJoin(['coupon' => Coupon::tableName()], 'coupon.id = user_coupon.coupon_id')
            ->select([
                'user_coupon.*',
                'coupon.title',
                'coupon.with_id',
                'coupon.used',
                'coupon.with_amount',
                'coupon.used_amount',
            ]);
        return $query;
    }

}