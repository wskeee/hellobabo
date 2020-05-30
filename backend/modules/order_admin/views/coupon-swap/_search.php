<?php

use common\models\order\CouponSwap;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\order\searchs\CouponSwapSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coupon-swap-search pull-right">

    <?php $form = ActiveForm::begin([
        'id' => 'search-form',
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <!-- coupon_id -->
    <?= Html::hiddenInput('coupon_id', $model->coupon_id) ?>

    <!-- code -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown">
            <?=
            Html::activeTextInput($model, 'code', [
                'class' => 'form-control',
                'placeholder' => $model->getAttributeLabel('code'),
                'onBlur' => 'submitForm()',
            ])
            ?>
        </div>
    </div>

    <!-- 是否兑换 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown" style="width:150px">
            <?=
            Select2::widget([
                'model' => $model,
                'attribute' => 'is_swap',
                'data' => CouponSwap::$isSwapNames,
                'options' => ['placeholder' => $model->getAttributeLabel('is_swap')],
                'pluginOptions' => ['allowClear' => true],
                'pluginEvents' => ['change' => 'function(){ submitForm()}']
            ])
            ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">

    // 提交表单
    function submitForm() {
        $('#search-form').submit();
    }
</script>
