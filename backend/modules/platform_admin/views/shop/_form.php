<?php

use common\models\AdminUser;
use common\widgets\webuploader\ImagePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\shop\Shop */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shop-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->widget(Select2::class, [
        'data' => AdminUser::getUserByType(AdminUser::TYPE_OWNER)
    ]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'logo_url')->widget(ImagePicker::class) ?>

    <?= $form->field($model, 'income_value')->textInput(['maxlength' => true,'placeholder' => '0~1为百分比，1~N等值']) ?>

    <?= $form->field($model, 'des')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'status')->textInput() ?>

    <?php // $form->field($model, 'cover_url')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
