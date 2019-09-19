<?php

namespace apiend\modules\v1\actions\activity;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\activity\VoteActivity;

/**
 * 获取最新活动
 *
 * @author Administrator
 */
class GetNew extends BaseAction
{

    public function run()
    {
        $time = time();
        $model = VoteActivity::find()
                ->where(['<=', 'start_time', $time])
                ->andWhere(['>=', 'end_time', $time])
                ->one();

        return new Response(Response::CODE_COMMON_OK, null, $model);
    }

}
