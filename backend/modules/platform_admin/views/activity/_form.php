<?php

use common\widgets\webuploader\ImagePicker;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\activity\Activity */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="activity-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cover_url')->widget(ImagePicker::class) ?>

    <?= $form->field($model, 'share_poster_url')->widget(ImagePicker::class) ?>

    <?php /*= $form->field($model, 'content')->textarea(['rows' => 6]) */?>

    <?= $form->field($model, 'start_time')->widget(DatePicker::class)?>

    <?= $form->field($model, 'end_time')->widget(DatePicker::class) ?>

    <?= $form->field($model, 'setting')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
