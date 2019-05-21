<?php

namespace apiend\modules\v1\actions\user;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\UserAddress;
use Yii;

/**
 * 更新地址
 *
 * @author Administrator
 */
class UpdateAddress extends BaseAction
{
    /* 必须参数 */

    protected $requiredParams = ['id'];

    public function run()
    {
        $address = UserAddress::findOne(['id' => $this->getSecretParam('id')]);
        if ($address == null) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => Yii::t('app', 'Address')]);
        }
        $address->load($this->secretParams, "");
        if ($address->validate() && $address->save()) {
            return new Response(Response::CODE_COMMON_OK, null, $address);
        } else {
            return new Response(Response::CODE_COMMON_SAVE_DB_FAIL, null, $address->getErrorSummary(true));
        }
    }

}
