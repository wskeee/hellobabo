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

    public function run()
    {
        if (!$this->verify()) {
            return $this->verifyError;
        }
        if (count($notfounds = $this->checkRequiredParams($this->getSecretParams(), ['id'])) != 0) {
            return new Response(Response::CODE_COMMON_MISS_PARAM, null, null, ['param' => implode(',', $notfounds)]);
        }
        $address = UserAddress::findOne(['id' => $this->getSecretParam('id')]);
        if ($address == null) {
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
