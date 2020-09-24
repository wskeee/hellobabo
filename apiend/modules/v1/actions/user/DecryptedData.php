<?php

namespace apiend\modules\v1\actions\user;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\User;
use Yii;

/**
 * 解密
 */
class DecryptedData extends BaseAction
{

    public function run()
    {
        /* @var $user User */
        $user = Yii::$app->user->identity;

        $code = $this->getSecretParam('code');
        if($code){
            $data = Yii::$app->wechat->miniProgram->auth->session($code);
            $session_key = $data['session_key'];
            $user->auths->credential = $session_key;
            $user->auths->save();
        }else{
            $session_key = $user->auths->credential;
        }
        
        $decryptedData = Yii::$app->wechat->miniProgram->encryptor->decryptData(
                $session_key,
                $this->getSecretParam('iv'), 
                $this->getSecretParam('encryptedData'));
        
        return new Response(Response::CODE_COMMON_OK, null, $decryptedData);
    }

}
