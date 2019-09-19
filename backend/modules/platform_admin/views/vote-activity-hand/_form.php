<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\activity\VoteActivityHand */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vote-activity-hand-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'num')->textInput() ?>

    <?= $form->field($model, 'activity_id')->textInput() ?>

    <?= $form->field($model, 'target_user_id')->textInput() ?>

    <?= $form->field($model, 'target_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'target_age')->textInput() ?>

    <?= $form->field($model, 'target_img')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'check_status')->textInput() ?>

    <?= $form->field($model, 'check_feedback')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
