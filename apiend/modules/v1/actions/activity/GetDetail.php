<?php

namespace apiend\modules\v1\actions\activity;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\activity\VoteActivity;

/**
 * 获取活动详情
 *
 * @author Administrator
 */
class GetDetail extends BaseAction
{
    protected $requiredParams = ['activity_id'];
    
    public function run()
    {
        $activity_id = $this->getSecretParam('activity_id');
        
        $model = VoteActivity::findOne(['id' => $activity_id]);

        return new Response(Response::CODE_COMMON_OK, null, $model->toDetail());
    }

}
