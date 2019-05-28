<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\order\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'order_sn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'goods_id')->textInput() ?>

    <?= $form->field($model, 'goods_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'goods_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'goods_num')->textInput() ?>

    <?= $form->field($model, 'spec_id')->textInput() ?>

    <?= $form->field($model, 'spec_key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'spec_key_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_status')->textInput() ?>

    <?= $form->field($model, 'work_status')->textInput() ?>

    <?= $form->field($model, 'user_note')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'play_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'play_sn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'play_at')->textInput() ?>

    <?= $form->field($model, 'init_at')->textInput() ?>

    <?= $form->field($model, 'upload_finish_at')->textInput() ?>

    <?= $form->field($model, 'design_at')->textInput() ?>

    <?= $form->field($model, 'print_at')->textInput() ?>

    <?= $form->field($model, 'shipping_at')->textInput() ?>

    <?= $form->field($model, 'confirm_at')->textInput() ?>

    <?= $form->field($model, 'consignee')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zipcode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'country')->textInput() ?>

    <?= $form->field($model, 'province')->textInput() ?>

    <?= $form->field($model, 'city')->textInput() ?>

    <?= $form->field($model, 'district')->textInput() ?>

    <?= $form->field($model, 'town')->textInput() ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_recommend')->textInput() ?>

    <?= $form->field($model, 'recommend_by')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
