<?php

namespace apiend\modules\v1\actions\user;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\UserAddress;
use Yii;
use yii\db\Expression;

/**
 * 增加地址
 *
 * @author Administrator
 */
class AddAddress extends BaseAction
{

    public function run()
    {
        $address = new UserAddress([
            'user_id' => Yii::$app->user->id
        ]);
        $address->load($this->secretParams, "");

        if ($address->validate() && $address->save()) {
            if ($address->is_default == 1) {
                UserAddress::updateAll(['is_default' => new Expression("IF(id=$address->id,1,0)")], ['user_id' => Yii::$app->user->id]);
            }
            return new Response(Response::CODE_COMMON_OK, null, $address);
        } else {
            return new Response(Response::CODE_COMMON_SAVE_DB_FAIL, null, $address->getErrorSummary(true));
        }
    }

}
