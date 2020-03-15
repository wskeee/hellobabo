<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\goods\searchs\GoodsMaterialValueItemSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="goods-material-value-item-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'material_value_id') ?>

    <?= $form->field($model, 'source_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'effect_url') ?>

    <?php // echo $form->field($model, 'source_url') ?>

    <?php // echo $form->field($model, 'is_del') ?>

    <?php // echo $form->field($model, 'des') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
