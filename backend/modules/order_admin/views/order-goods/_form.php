<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\order\OrderGoods */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-goods-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'order_id')->textInput() ?>

    <?= $form->field($model, 'order_sn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'goods_id')->textInput() ?>

    <?= $form->field($model, 'goods_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'goods_img')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'goods_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'goods_cost')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'goods_num')->textInput() ?>

    <?= $form->field($model, 'user_cover_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'spec_id')->textInput() ?>

    <?= $form->field($model, 'spec_key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'spec_key_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'scene_num')->textInput() ?>

    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'init_at')->textInput() ?>

    <?= $form->field($model, 'upload_finish_at')->textInput() ?>

    <?= $form->field($model, 'design_at')->textInput() ?>

    <?= $form->field($model, 'print_at')->textInput() ?>

    <?= $form->field($model, 'is_del')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
