<?php

namespace apiend\modules\v1\actions\user;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\UserAddress;

/**
 * 增加地址
 *
 * @author Administrator
 */
class AddAddress extends BaseAction
{

    public function run()
    {
        $address = new UserAddress();
        $address->load($this->secretParams,"");
        if ($address->validate() && $address->save()) {
            return new Response(Response::CODE_COMMON_OK, null, $address);
        }else{
            return new Response(Response::CODE_COMMON_SAVE_DB_FAIL, null, $address->getErrorSummary(true));
        }
    }

}
