<?php

namespace apiend\modules\v1\actions\user;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\system\Region;
use common\models\UserAddress;

/**
 * 更新地址准备，返回地址详细及省市区数据
 *
 * @author wskeee
 */
class UpdateAddressReady extends BaseAction
{

    protected $requiredParams = ['address_id'];

    public function run()
    {
        $address = UserAddress::findOne(['id' => $this->getSecretParam('address_id')]);
        if ($address) {
            return new Response(Response::CODE_COMMON_OK, null, [
                'address' => $address,
                'regions' => Region::getRegionByJson(3),
            ]);
        } else {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => '地址']);
        }
    }

}
