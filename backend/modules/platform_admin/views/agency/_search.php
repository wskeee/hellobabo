<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\platform\searchs\AgencySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="agency-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'des') ?>

    <?= $form->field($model, 'cover_url') ?>

    <?= $form->field($model, 'level') ?>

    <?php // echo $form->field($model, 'admin_id') ?>

    <?php // echo $form->field($model, 'contacts_name') ?>

    <?php // echo $form->field($model, 'contacts_phone') ?>

    <?php // echo $form->field($model, 'idcard') ?>

    <?php // echo $form->field($model, 'idcard_img_url') ?>

    <?php // echo $form->field($model, 'province') ?>

    <?php // echo $form->field($model, 'city') ?>

    <?php // echo $form->field($model, 'district') ?>

    <?php // echo $form->field($model, 'town') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'apply_time') ?>

    <?php // echo $form->field($model, 'check_id') ?>

    <?php // echo $form->field($model, 'check_time') ?>

    <?php // echo $form->field($model, 'check_feedback') ?>

    <?php // echo $form->field($model, 'is_del') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
