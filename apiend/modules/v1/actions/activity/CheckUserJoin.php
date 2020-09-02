<?php


namespace apiend\modules\v1\actions\activity;


use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\services\ActivityService;

class CheckUserJoin extends BaseAction
{
    public function run()
    {
        $code = $this->getSecretParam('code', '');
        $id = $this->getSecretParam('id', 0);
        $user_id = $this->getSecretParam('user_id', \Yii::$app->user->id);

        if (empty($code) && empty($id)) {
            return new Response(Response::CODE_COMMON_MISS_PARAM, null, null, ['params' => 'code|id 至少传一个']);
        }

        if (!empty($id)) {
            $is_join = ActivityService::checkUserIsJoin($id, $user_id);
        } else {
            $activity = ActivityService::getActivityByCode($code);
            $is_join = ActivityService::checkUserIsJoin($activity->id, $user_id);
        }

        return new Response(Response::CODE_COMMON_OK, null, ['is_join' => $is_join]);
    }
}