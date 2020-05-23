<?php

use common\models\goods\Goods;
use common\models\order\Coupon;
use common\widgets\webuploader\ImagePicker;
use kartik\select2\Select2;
use kartik\widgets\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\order\Coupon */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coupon-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'used')->widget(Select2::class, [
        'data' => Coupon::$usedNames,
        'options' => ['onchange' => 'onUsedChanged($(this));']
    ]); ?>

    <?= $form->field($model, 'type')->radioList(Coupon::$typeNames, ['onchange' => 'onTypeChanged($(this));']) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'icon_url')->widget(ImagePicker::class); ?>

    <?= $form->field($model, 'des')->textarea(['maxlength' => true]) ?>

    <?= $form->field($model, 'with_id')->widget(Select2::class, [
        'options' => ['placeholder' => Yii::t('app', 'Select Placeholder'),],
        'data' => Goods::getUseableList(),
    ]); ?>

    <?= $form->field($model, 'with_amount')->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'used_amount')->textInput(['type' => 'number', 'step' => "0.01"]) ?>

    <?= $form->field($model, 'quota')->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'start_time')->widget(DatePicker::class, [
        'pluginOptions' => [
            'convertFormat' => true,
            'autoclose' => true,
            'todayHighlight' => true,
            'format' => 'yyyy-mm-dd',
        ],
        'options' => ['placeholder' => '不限制',]
    ]) ?>

    <?= $form->field($model, 'end_time')->widget(DatePicker::class, [
        'pluginOptions' => [
            'convertFormat' => true,
            'autoclose' => true,
            'todayHighlight' => true,
            'format' => 'yyyy-mm-dd',
        ],
        'options' => ['placeholder' => '不限制',]
    ]) ?>

    <?= $form->field($model, 'valid_type')->radioList(Coupon::$validTypeNames, ['onchange' => 'onValidTypeChanged();']) ?>

    <?= $form->field($model, 'valid_start_time')->widget(DatePicker::class, [
        'pluginOptions' => [
            'convertFormat' => true,
            'autoclose' => true,
            'todayHighlight' => true,
            'format' => 'yyyy-mm-dd',
        ],
        'options' => ['placeholder' => '不限制',]
    ]) ?>

    <?= $form->field($model, 'valid_end_time')->widget(DatePicker::class, [
        'pluginOptions' => [
            'convertFormat' => true,
            'autoclose' => true,
            'todayHighlight' => true,
            'format' => 'yyyy-mm-dd',
        ],
        'options' => ['placeholder' => '不限制',]
    ]) ?>

    <?= $form->field($model, 'valid_days')->textInput() ?>

    <?= $form->field($model, 'status')->widget(Select2::class, ['data' => Coupon::$statusNames]); ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>

    window.onload = function () {
        onUsedChanged($('#coupon-used'));
        onTypeChanged($('#coupon-type'));
        onValidTypeChanged();
    };

    /**
     * 类型改变 满减OR无门坎
     **/
    function onTypeChanged($dom) {
        $dom = $dom.find(':checked');
        var value = $dom.val();
        var is_nothreshold = value == <?= Coupon::TYPE_NO_THRESHOLD ?>;
        if (is_nothreshold) {
            $('#coupon-with_amount').prop('readonly', true).val(0);
        } else {
            $('#coupon-with_amount').prop('readonly', false);
        }
    }

    /**
     * 卷用途改变事件
     * @param $dom Dom
     */
    function onUsedChanged($dom) {
        var value = $dom.val();
        var isGoods = value == <?= Coupon::USED_GOODS ?>;
        if (isGoods) {
            $('.field-coupon-with_id').show();
            $('#coupon-with_id').prop('disabled', false);
        } else {
            $('.field-coupon-with_id').hide();
            $('#coupon-with_id').prop('disabled', true);
        }
    }

    /**
     * 有效类型改变事件
     */
    function onValidTypeChanged() {
        var value = $('input[name="Coupon[valid_type]"]:checked').val();
        var isAbsolute = value == <?= Coupon::VALID_TYPE_ABSOLUTE ?>;
        if (isAbsolute) {
            $('.field-coupon-valid_start_time').show();
            $('.field-coupon-valid_end_time').show();
            $('.field-coupon-valid_days').hide();
            $('#coupon-valid_start_time').prop('disabled', false);
            $('#coupon-valid_end_time').prop('disabled', false);
            $('#coupon-valid_days').prop('disabled', true);
        } else {
            $('.field-coupon-valid_start_time').hide();
            $('.field-coupon-valid_end_time').hide();
            $('.field-coupon-valid_days').show();
            $('#coupon-valid_start_time').prop('disabled', true);
            $('#coupon-valid_end_time').prop('disabled', true);
            $('#coupon-valid_days').prop('disabled', false);
        }
    }
</script>