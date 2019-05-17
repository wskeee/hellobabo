<?php

namespace apiend\modules\v1\actions\user;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\User;
use common\models\UserAddress;
use Yii;

/**
 * 获取用户地址列表
 *
 * @author Administrator
 */
class GetAddressList extends BaseAction
{

    public function run()
    {
        if (!$this->verify()) {
            return $this->verifyError;
        }
        /* @var $user User */
        $user = Yii::$app->user->identity;
        $list = UserAddress::find()
                        ->where([
                            'user_id' => $user,
                            'is_del' => 0,
                        ])->all();

        return new Response(Response::CODE_COMMON_OK, null, $list);
    }

}
