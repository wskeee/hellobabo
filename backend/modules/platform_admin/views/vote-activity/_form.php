<?php

use common\models\activity\VoteActivity;
use common\widgets\ueditor\UEDitor;
use common\widgets\webuploader\ImagePicker;
use kartik\widgets\DatePicker;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model VoteActivity */
/* @var $form ActiveForm */
?>

<div class="vote-activity-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cover_url')->widget(ImagePicker::class) ?>

    <?= $form->field($model, 'start_time')->widget(DatePicker::class,[
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd'
        ]
    ]) ?>

    <?= $form->field($model, 'end_time')->widget(DatePicker::class,[
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd'
        ]
    ]) ?>
    
    <?= $form->field($model, 'content')->widget(UEDitor::class, []) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
