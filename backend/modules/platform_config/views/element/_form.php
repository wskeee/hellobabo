<?php

use common\models\goods\GoodsElement;
use common\widgets\webuploader\ImagePicker;
use common\widgets\webuploader\SoundPicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model GoodsElement */
/* @var $form ActiveForm */
?>

<div class="goods-element-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->widget(Select2::class,[
        'data' => GoodsElement::$typeNameMap
    ]) ?>
    
    <div style="display:flex;">
        <div style="margin-right: 20px;">
            <?= $form->field($model, 'thumb_url')->widget(ImagePicker::class) ?>
        </div>
        <div style="margin-right: 20px;">
            <?= $form->field($model, 'img_url')->widget(ImagePicker::class) ?>
        </div>
        <div style="margin-right: 20px;">
            <?= $form->field($model, 'sound_url')->widget(SoundPicker::class) ?>
        </div>
    </div>

    <?= $form->field($model, 'des')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'config')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
