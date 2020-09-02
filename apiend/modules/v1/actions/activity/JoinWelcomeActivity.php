<?php


namespace apiend\modules\v1\actions\activity;


use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\activity\Activity;
use common\models\order\Coupon;
use common\services\ActivityService;
use common\services\CouponService;
use yii\helpers\ArrayHelper;

/**
 * 领取新人卷
 * Class JoinWelcomeActivity
 * @package apiend\modules\v1\actions\activity
 */
class JoinWelcomeActivity extends BaseAction
{
    public function run()
    {
        $user_id = \Yii::$app->user->id;
        $activity = ActivityService::getActivityByCode('welcome_activity');
        if (!$activity || $activity->status == Activity::STATUS_DISABLED) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, '活动不存在或者已下架');
        }

        if (ActivityService::checkUserIsJoin($activity->id, $user_id)) {
            return new Response(Response::CODE_COMMON_FORBIDDEN, '该活动只对新人有效！');
        } else {
            $list = CouponService::getUseableCouponByUsed($user_id, Coupon::USED_NEWER);
            $tran = \Yii::$app->db->beginTransaction();
            try {
                // 成功领取
                $receive_user_coupons = [];
                foreach ($list as $coupon) {
                    $res = CouponService::receive($coupon['id'], $user_id);
                    if (!$res['result']) {
                        throw new \Exception($res['msg']);
                    } else {
                        $receive_user_coupons[] = $res['data'];
                    }
                }
                ActivityService::joinActivity($activity->id, $user_id);
                // 查询卷详情数据
                $user_coupons = CouponService::getUserCouponByIds(ArrayHelper::getColumn($receive_user_coupons, 'id'));
                $tran->commit();
                return new Response(Response::CODE_COMMON_OK, null, $user_coupons);
            } catch (\Exception $e) {
                $tran->rollBack();
                return new Response(Response::CODE_COMMON_SAVE_DB_FAIL, $e->getMessage());
            }
        }
    }
}