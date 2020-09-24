<?php


namespace apiend\modules\v1\actions\activity;


use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\activity\Activity;
use common\services\ActivityService;

class GetActivityByCode extends BaseAction
{
    protected $requiredParams = ['code'];

    public function run()
    {
        $code = $this->getSecretParam('code');
        return new Response(Response::CODE_COMMON_OK, null, ActivityService::getActivityByCode($code));
    }
}