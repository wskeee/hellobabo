<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\order\Coupon */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coupon-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'icon_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'des')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'used')->textInput() ?>

    <?= $form->field($model, 'type')->textInput() ?>

    <?= $form->field($model, 'with_special')->textInput() ?>

    <?= $form->field($model, 'with_id')->textInput() ?>

    <?= $form->field($model, 'with_amount')->textInput() ?>

    <?= $form->field($model, 'used_amount')->textInput() ?>

    <?= $form->field($model, 'quota')->textInput() ?>

    <?= $form->field($model, 'take_count')->textInput() ?>

    <?= $form->field($model, 'used_count')->textInput() ?>

    <?= $form->field($model, 'start_time')->textInput() ?>

    <?= $form->field($model, 'end_time')->textInput() ?>

    <?= $form->field($model, 'valid_type')->textInput() ?>

    <?= $form->field($model, 'valid_start_time')->textInput() ?>

    <?= $form->field($model, 'valid_end_time')->textInput() ?>

    <?= $form->field($model, 'valid_days')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
