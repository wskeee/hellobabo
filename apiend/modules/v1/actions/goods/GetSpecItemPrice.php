<?php

namespace apiend\modules\v1\actions\goods;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\goods\GoodsSpecPrice;

/**
 * 获取规格项价格
 *
 * @author Administrator
 */
class GetSpecItemPrice extends BaseAction
{

    protected $requiredParams = ['gsp_key'];

    public function run()
    {
        $gsp_key = $this->getSecretParam('gsp_key');
        $gsp = GoodsSpecPrice::findOne(['spec_key' => $gsp_key]);
        if ($gsp) {
            return new Response(Response::CODE_COMMON_OK, null, $gsp);
        }else{
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null,['param' => '价格项']);
        }
    }

}
