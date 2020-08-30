<?php


namespace common\services;


use common\models\activity\Activity;
use common\models\activity\ActivityJoinRecord;

class ActivityService
{
    use BaseServiceTrait;

    /**
     * 通过 Code 查询对应活动
     * @param $code
     * @return Activity|null
     */
    public static function getActivityByCode($code)
    {
        return Activity::findOne(['code' => $code]);
    }

    public static function checkUserIsJoin($activity_id, $user_id)
    {
        //return ActivityJoinRecord::findOne([])
    }
}