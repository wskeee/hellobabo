<?php

namespace apiend\modules\v1\actions\user;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\UserAddress;
use Yii;

/**
 * 删除地址
 * @author Administrator
 */
class DelAddress extends BaseAction
{
    /* 必须参数 */

    protected $requiredParams = ['address_id'];

    public function run()
    {
        $address = UserAddress::findOne(['id' => $this->getSecretParam('address_id')]);
        if ($address == null || $address->is_del == 1) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => Yii::t('app', 'Address')]);
        }
        $address->is_del = 1;
        if ($address->validate() && $address->save()) {
            return new Response(Response::CODE_COMMON_OK, null, $address);
        } else {
            return new Response(Response::CODE_COMMON_SAVE_DB_FAIL, null, $address->getErrorSummary(true));
        }
    }

}
