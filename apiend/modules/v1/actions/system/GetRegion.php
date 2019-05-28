<?php

namespace apiend\modules\v1\actions\system;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\system\Region;

/**
 * 返回省市区数据
 *
 * @author wskeee
 */
class GetRegion extends BaseAction
{

    public function run()
    {
        $regions = Region::getRegionByJson(3);

        return new Response(Response::CODE_COMMON_OK, null, $regions);
    }

}
