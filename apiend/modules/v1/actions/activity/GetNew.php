<?php

namespace apiend\modules\v1\actions\activity;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\system\Banner;

/**
 * 获取最新活动
 *
 * @author Administrator
 */
class GetNew extends BaseAction
{

    public function run()
    {
        $model = Banner::findOne(['is_publish' => 1]);

        return new Response(Response::CODE_COMMON_OK, null, ['banner' => $model]);
    }

}
