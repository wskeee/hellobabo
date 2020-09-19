<?php

use common\models\LoginForm;
use merchant\assets\SiteAssets;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $form ActiveForm */
/* @var $model LoginForm */

SiteAssets::register($this);

$this->title = Yii::t('app', 'Login');

?>

<div class="site-login">
    <div>
        <div class="logo-box">
            <img src="/statics/imgs/site/logo.png">
        </div>
        <div class="row">
            <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'fieldConfig' => [
                        'template' => "{label}<div class=\"col-lg-12 col-md-12\">{input}</div>\n<div class=\"col-lg-7 col-md-7\">{error}</div>",
                        'labelOptions' => ['class' => 'control-label', 'style' => ['color' => '#999999', 'font-weight' => 'normal', 'padding-left' => '0']],
                    ],
            ]); ?>
            <!--密码登录-->
            <div class="pass-login-covers">
                <div class="field-loginform-username required">
                    <div class="field-row">
                        <label class="control-label" for="loginform-username">账号</label>
                        <div class="control-input">
                            <input type="text" id="loginform-username" class="form-control" name="LoginForm[username]" placeholder="请输入用户名" aria-required="true" value="<?=$model->username?>">
                            <?php if(isset($model->errors['username'])): ?>
                                <div><p class="help-block help-block-error"><?= $model->errors['username'][0] ?></p></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="field-loginform-password required">
                    <div class="field-row">
                        <label class="control-label" for="loginform-password">密码</label>
                        <div class="control-input">
                            <input type="password" id="loginform-password" class="form-control" name="LoginForm[password]" value="<?=$model->username?>" placeholder="请输入密码" aria-required="true">
                            <?php if(isset($model->errors['password'])): ?>
                                <div><p class="help-block help-block-error"><?= $model->errors['password'][0] ?></p></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!--记住登陆/忘记密码/注册-->
            <div class="remeber-forget" style="display: none;">
                <div class="field-row">
                    <label class="control-label" for="loginform-username"></label>
                    <div class="control-input" style="width: 180px;text-align: right;">
                        <div class="checkbox">
                            <input type="hidden" name="LoginForm[rememberMe]" value="0">
                            <input type="checkbox" id="loginform-rememberme" name="LoginForm[rememberMe]" value="1" checked="">
                            <label for="loginform-rememberme">
                                记住账号
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <!--登录按钮-->
            <div class="field-row">
                <?= Html::submitButton('登录', [
                    'id' => 'submit-btn',
                    'name' => 'login-button',
                    'class' => 'btn btn-primary col-xs-12',
                ]) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?php
$js = <<<JS
    
JS;
    $this->registerJs($js, View::POS_READY);
?>
