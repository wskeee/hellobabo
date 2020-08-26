<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\shop\searchs\ShopSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shop-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'logo_url') ?>

    <?= $form->field($model, 'income_value') ?>

    <?php // echo $form->field($model, 'des') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'cover_url') ?>

    <?php // echo $form->field($model, 'goods_count') ?>

    <?php // echo $form->field($model, 'order_count') ?>

    <?php // echo $form->field($model, 'all_income') ?>

    <?php // echo $form->field($model, 'real_income') ?>

    <?php // echo $form->field($model, 'day_income') ?>

    <?php // echo $form->field($model, 'month_income') ?>

    <?php // echo $form->field($model, 'last_income_time') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
