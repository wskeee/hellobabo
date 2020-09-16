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
    <div class="panel">
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
                        <div>
                            <input type="text" id="loginform-username" class="form-control" name="LoginForm[username]" placeholder="账号" aria-required="true">
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-7"><p class="help-block help-block-error"></p></div>
                </div>
                <div class="field-loginform-password required">
                    <div class="field-row">
                        <label class="control-label" for="loginform-password">密码</label>
                        <div>
                            <input type="password" id="loginform-password" class="form-control" name="LoginForm[password]" value="" placeholder="密码" aria-required="true">
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-7"><p class="help-block help-block-error"></p></div>
                </div>
            </div>
            <!--登录按钮-->
            <div class="col-xs-12 button">
                <?= Html::submitButton('登录', [
                    'name' => 'login-button',
                    'class' => 'btn btn-primary col-xs-12',
                ]) ?>
            </div>
            <!--记住登陆/忘记密码/注册-->
            <div class="remeber-forget">
                <?= $form->field($model, 'rememberMe', [
                    'options' => [
                        'class' => 'col-xs-6',
                    ],
                    //'template' => "{label}\n<div class=\"col-lg-12\">{input}</div>",
                ])->checkbox([
                    'template' => "<div class=\"checkbox\"><label for=\"loginform-rememberme\">{input}自动登录</label></div>"
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
