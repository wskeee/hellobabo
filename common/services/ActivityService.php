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

    /**
     * 检查用户有没有参加过指定活动
     * @param int $activity_id
     * @param int $user_id
     * @return bool
     */
    public static function checkUserIsJoin($activity_id, $user_id)
    {
        return ActivityJoinRecord::findOne(['activity_id' => $activity_id, 'user_id' => $user_id, 'is_del' => 0]) != null;
    }

    /**
     * 加入活动
     * @param int $activity_id 活动ID
     * @param int $user_id 用户ID
     * @param string $value 附加值
     * @return bool
     * @throws
     */
    public static function joinActivity($activity_id, $user_id, $value = '')
    {
        $record = ActivityJoinRecord::findOne(['activity_id' => $activity_id, 'user_id' => $user_id]);
        if ($record) {
            $record->is_del = 0;
        } else {
            $record = new ActivityJoinRecord(['activity_id' => $activity_id, 'user_id' => $user_id, 'value' => $value]);
        }
        $result = $record->save();
        if (!$result) {
            throw new \Exception(implode(',', $record->getErrorSummary(true)));
        }
        return $result;
    }
}