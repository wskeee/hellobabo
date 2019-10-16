<?php

use common\models\goods\Goods;
use common\models\goods\SceneGroup;
use common\widgets\webuploader\ImagePicker;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model SceneGroup */
/* @var $form ActiveForm */
?>

<div class="scene-group-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <!-- 相关 -->
    <?= $form->field($model, 'relate_id')->widget(Select2::class, [
        'data' => ArrayHelper::map(Goods::find()->all(), 'id', 'goods_name'),
        'options' => ['placeholder' => Yii::t('app', 'Select Placeholder')],
    ]) ?>
    
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'cover_url')->widget(ImagePicker::class) ?>

    <?= $form->field($model, 'des')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
