<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\platform\Withdrawals */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="withdrawals-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'order_sn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'need_check')->textInput() ?>

    <?= $form->field($model, 'check_user_id')->textInput() ?>

    <?= $form->field($model, 'check_at')->textInput() ?>

    <?= $form->field($model, 'check_feedback')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tran_sn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pay_account')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pay_realname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pay_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pay_at')->textInput() ?>

    <?= $form->field($model, 'pay_error_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'des')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
