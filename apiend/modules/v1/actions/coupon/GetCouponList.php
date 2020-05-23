<?php

namespace apiend\modules\v1\actions\coupon;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\goods\Goods;
use common\models\order\Coupon;
use common\services\CouponService;
use Yii;

class GetCouponList extends BaseAction
{
    /**
     * 卷类型
     * 1、返回新手卷
     * 2、平台卷
     * 3、商品类型卷 (有传商品ID)
     * 4、商品专属卷 (有传商品ID)
     *
     * 返回条件
     * 1、已发布
     * 2、有效时间内
     * 3、未领取过
     *
     */
    public function run()
    {
        $user = Yii::$app->user->identity;
        $time = time();
        $goods_id = $this->getSecretParam('goods_id');
        if ($goods_id) {
            $goods = Goods::findOne($goods_id);
            if (!$goods) {
                return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => Yii::t('app', 'Goods')]);
            }
        }

        $query = Coupon::find()
            // 已经发布
            ->where(['status' => Coupon::STATUS_PUBLISHED,])
            // 到了指定发布时间和结束时间内
            ->andWhere(['<=', 'start_time', $time])
            ->andWhere(['>=', 'end_time', $time])
            ->andWhere(['>', 'quota', 'take_count']);

        if ($goods_id) {
            $query->andWhere(['or',
                // 新手和平台卷无需其它条件
                ['used' => [Coupon::USED_NEWER, Coupon::USED_PLATFORM]],
                // 商品类型卷，类型匹配
                //['used' => Coupon::USED_TYPE, 'with_id' => $goods->type],
                // 商品卷
                ['used' => Coupon::USED_GOODS, 'with_id' => $goods->$goods_id],
            ]);
        } else {
            $query->andWhere(['used' => [Coupon::USED_NEWER, Coupon::USED_PLATFORM, Coupon::USED_GOODS]]);
        }

        // 过滤已领取的卷
        //$query->andFilterWhere(['NOT IN', 'id', $user_coupon_ids]);

        // 结果
        $list = $query->asArray()->all();
        $coupon_ids = array_column($list, 'id');
        if (!empty($coupon_ids)) {
            $user_coupon_map = CouponService::getUserCouponCount($user->id, $coupon_ids);
            foreach ($list as &$coupon){
                $coupon_count = isset($user_coupon_map[$coupon['id']]) ? $user_coupon_map[$coupon['id']] : 0;
                // 是否已领取过
                $coupon['has_take'] = $coupon_count > 0;
                // 还能否继续领取
                $coupon['can_take'] = $coupon_count ? $coupon_count < $coupon['user_max_count'] : true;
                // 总过领了多少张
                $coupon['user_take_count'] = $coupon_count;
            }
        }

        return new Response(Response::CODE_COMMON_OK, null, $list);
    }
}