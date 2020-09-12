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
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <!--密码登录-->
            <div class="pass-login-covers">
                <?= $form->field($model, 'username',[
                    'options' => [
                        'class' => 'col-xs-12 attr-name',
                    ],
                    'inputOptions' => ['placeholder' => '账号'],
                    'template' => "<div class=\"col-xs-12\" style=\"padding:0px;\">{input}</div>\n<div class=\"col-xs-10\" style=\"padding: 0px 5px;\">{error}</div>"
                ]); ?>

                <?= $form->field($model, 'password', [
                    'options' => [
                        'class' => 'col-xs-12 attr-pass',
                    ],
                    'inputOptions' => ['placeholder' => '密码'],
                    'template' => "<div class=\"col-xs-12\" style=\"padding:0px;\">{input}</div>\n<div class=\"col-xs-10\" style=\"padding: 0px 5px;\">{error}</div>"
                ])->passwordInput() ?>
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
