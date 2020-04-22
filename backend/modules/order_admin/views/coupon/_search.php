<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\order\searchs\CouponSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coupon-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'icon_url') ?>

    <?= $form->field($model, 'des') ?>

    <?= $form->field($model, 'used') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'with_special') ?>

    <?php // echo $form->field($model, 'with_id') ?>

    <?php // echo $form->field($model, 'with_amount') ?>

    <?php // echo $form->field($model, 'used_amount') ?>

    <?php // echo $form->field($model, 'quota') ?>

    <?php // echo $form->field($model, 'take_count') ?>

    <?php // echo $form->field($model, 'used_count') ?>

    <?php // echo $form->field($model, 'start_time') ?>

    <?php // echo $form->field($model, 'end_time') ?>

    <?php // echo $form->field($model, 'valid_type') ?>

    <?php // echo $form->field($model, 'valid_start_time') ?>

    <?php // echo $form->field($model, 'valid_end_time') ?>

    <?php // echo $form->field($model, 'valid_days') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
