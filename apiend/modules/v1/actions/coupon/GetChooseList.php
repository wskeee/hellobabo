<?php


namespace apiend\modules\v1\actions\coupon;


use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\goods\Goods;
use common\models\order\Coupon;
use common\services\CouponService;
use Yii;
use yii\helpers\ArrayHelper;

class GetChooseList extends BaseAction
{
    protected $requiredParams = ['goods_id', 'amount'];

    public function run()
    {
        $user = Yii::$app->user->identity;
        $goods_id = $this->getSecretParam('goods_id');
        $amount = $this->getSecretParam('amount');
        // 可用的
        $useable = CouponService::getUseAbleCoupon($user->id, $goods_id, $amount);
        $useable_map = ArrayHelper::map($useable, 'id', 'id');
        // 未用的
        $unuse = CouponService::getUnuseCoupon($user->id);
        // 不可用的
        $unuseable = [];
        foreach ($unuse as $i => $item) {
            if (!isset($useable_map[$item['id']])) {
                $unuseable[] = $item;
            }
        }
        $data = [
            'coupon' => CouponService::getCouponBestByCoupons($useable, $amount),
            'useable' => $useable,
            'unuseable' => $unuseable,
        ];
        return new Response(Response::CODE_COMMON_OK, null, $data);
    }
}