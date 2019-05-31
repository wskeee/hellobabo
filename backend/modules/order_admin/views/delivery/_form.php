<?php

use common\models\order\WorkflowDelivery;
use common\models\platform\Express;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model WorkflowDelivery */
/* @var $form ActiveForm */
?>

<div class="workflow-delivery-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'send_type')->widget(Select2::class, [
        'data' => WorkflowDelivery::$sendTypeNameMap,
        'options' => ['placeholder' => Yii::t('app', 'All')],
    ]) ?>
    
    <?= $form->field($model, 'shipping_code')->widget(Select2::class, [
        'data' => Express::getExpress(),
        'options' => ['placeholder' => Yii::t('app', 'All')],
    ])?>

    <?= $form->field($model, 'shipping_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'invoice_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
