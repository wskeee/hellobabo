<?php

namespace apiend\modules\v1\actions\coupon;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\goods\Goods;
use common\models\order\Coupon;
use common\models\order\UserCoupon;

class GetCouponList extends BaseAction
{
    protected $requiredParams = ['goods_id'];

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
        $user = \Yii::$app->user->identity;
        $time = time();
        $goods_id = $this->getSecretParam('goods_id');
        $goods = Goods::findOne($goods_id);

        if (!$goods) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['params' => \Yii::t('app', 'Goods')]);
        }

        $user_coupon_ids = UserCoupon::find()->select('coupon_id')->where(['user_id' => $user->id])->column();

        $query = Coupon::find()
            // 已经发布
            ->where(['status' => Coupon::STATUS_PUBLISHED,])
            // 到了指定发布时间和结束时间内
            ->andWhere(['>=', 'start_time', $time])
            ->andWhere(['<=', 'end_time', $time])
            ->andWhere(['>', 'quota', 'take_count']);

        if ($goods) {
            $query->andWhere(['or',
                // 新手和平台卷无需其它条件
                ['used', 'IN', [Coupon::USED_NEWER, Coupon::USED_PLATFORM]],
                // 商品类型卷，类型匹配
                ['used' => Coupon::USED_TYPE, 'with_id' => $goods->type],
                // 商品卷
                ['used' => Coupon::USED_GOODS, 'with_id' => $goods->$goods_id],
            ]);
        } else {
            $query->andWhere(['used', 'IN', [Coupon::USED_NEWER, Coupon::USED_PLATFORM]]);
        }
    }
}