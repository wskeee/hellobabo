<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;

/**
 * 初始绘本准备
 * 返回绘本素材和场景
 */
class InitReady extends BaseAction
{

    public function run()
    {

        return new Response(Response::CODE_COMMON_OK, null, [
        ]);
    }

}
