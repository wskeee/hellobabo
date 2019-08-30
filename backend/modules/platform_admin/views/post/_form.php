<?php

use common\models\platform\Post;
use common\models\platform\PostType;
use common\widgets\ueditor\UEDitor;
use kartik\select2\Select2;
use kartik\widgets\SwitchInput;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Post */
/* @var $form ActiveForm */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(); ?>

    <?=
    $form->field($model, 'type_id')->widget(Select2::class, [
        'data' => PostType::getValues(),
        'options' => ['placeholder' => Yii::t('app', 'Select Placeholder')],
    ])
    ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_publish')->widget(SwitchInput::class) ?>

    <?= $form->field($model, 'content')->widget(UEDitor::class, ['options' => ['class' => 'edui-default']]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
