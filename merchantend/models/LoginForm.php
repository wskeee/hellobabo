<?php


namespace merchant\models;


use common\models\AdminUser;
use common\models\User;
use Yii;

class LoginForm extends \common\models\LoginForm
{
    public $userClass = AdminUser::class;

    public function login()
    {
        if ($this->validate()) {
            $userModel = $this->getUser();
            $hasLogin = Yii::$app->user->login($userModel, $this->rememberMe ? 3600 * 24 * 30 : 0);
            return $hasLogin;
        }

        return false;
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user) {
                $this->addError($attribute, Yii::t('app', 'User no longer exists or has been deactivated'));
            } elseif (!$user || $user->type != AdminUser::TYPE_OWNER || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('app', 'Incorrect username or password'));
            }
        }
    }
}