<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\order\searchs\OrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'order_sn') ?>

    <?= $form->field($model, 'goods_id') ?>

    <?= $form->field($model, 'goods_name') ?>

    <?= $form->field($model, 'goods_price') ?>

    <?php // echo $form->field($model, 'goods_num') ?>

    <?php // echo $form->field($model, 'spec_id') ?>

    <?php // echo $form->field($model, 'spec_key') ?>

    <?php // echo $form->field($model, 'spec_key_name') ?>

    <?php // echo $form->field($model, 'order_amount') ?>

    <?php // echo $form->field($model, 'order_status') ?>

    <?php // echo $form->field($model, 'work_status') ?>

    <?php // echo $form->field($model, 'user_note') ?>

    <?php // echo $form->field($model, 'play_code') ?>

    <?php // echo $form->field($model, 'play_sn') ?>

    <?php // echo $form->field($model, 'play_at') ?>

    <?php // echo $form->field($model, 'init_at') ?>

    <?php // echo $form->field($model, 'upload_finish_at') ?>

    <?php // echo $form->field($model, 'design_at') ?>

    <?php // echo $form->field($model, 'print_at') ?>

    <?php // echo $form->field($model, 'shipping_at') ?>

    <?php // echo $form->field($model, 'confirm_at') ?>

    <?php // echo $form->field($model, 'consignee') ?>

    <?php // echo $form->field($model, 'zipcode') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'country') ?>

    <?php // echo $form->field($model, 'province') ?>

    <?php // echo $form->field($model, 'city') ?>

    <?php // echo $form->field($model, 'district') ?>

    <?php // echo $form->field($model, 'town') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'is_recommend') ?>

    <?php // echo $form->field($model, 'recommend_by') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
