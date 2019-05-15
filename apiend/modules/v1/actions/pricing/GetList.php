<?php

namespace apiend\modules\v1\actions\pricing;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\platform\Pricing;

class GetList extends BaseAction
{

    public function run()
    {
        if (!$this->verify()) {
            return $this->verifyError;
        }
        
        $pricings = Pricing::findAll(['is_del' => 0]);

        return new Response(Response::CODE_COMMON_OK, null, $pricings);
    }

}
