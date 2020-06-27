<?php

use common\models\AdminUser;
use common\models\platform\Issue;
use common\utils\I18NUitl;
use kartik\daterange\DateRangePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\platform\searchs\AgencyServicerSearch */
/* @var $form yii\widgets\ActiveForm */
/* @var $agencys array */
?>

<div class="agency-servicer-search">

    <?php
    $form = ActiveForm::begin([
        'id' => 'search-form',
        'action' => ['index'],
        'method' => 'get',
    ]);
    ?>

    <!-- 代理商 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown" style="width:120px">
            <?=
            Select2::widget([
                'model' => $model,
                'attribute' => 'agency_id',
                'data' => $agencys,
                'options' => ['placeholder' => Yii::t('app', 'Agency')],
                'pluginOptions' => ['allowClear' => true],
                'pluginEvents' => ['change' => 'function(){ submitForm()}']
            ])
            ?>
        </div>
    </div>

    <!-- Order SN -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown" style="width:120px">
            <?=
            Html::activeTextInput($model, 'order_sn', [
                'class' => 'form-control',
                'placeholder' => Yii::t('app', 'Order Sn'),
                'onBlur' => 'submitForm()',])
            ?>
        </div>
    </div>

    <!-- 用户 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown" style="width:120px">
            <?=
            Html::activeTextInput($model, 'user_name', [
                'class' => 'form-control',
                'placeholder' => $model->getAttributeLabel('user_name'),
                'onBlur' => 'submitForm()',])
            ?>
        </div>
    </div>

    <!-- 手机 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown" style="width:120px">
            <?=
            Html::activeTextInput($model, 'user_phone', [
                'class' => 'form-control',
                'placeholder' => $model->getAttributeLabel('user_phone'),
                'onBlur' => 'submitForm()',])
            ?>
        </div>
    </div>

    <!-- 自身 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown" style="width:120px">
            <?=
            Select2::widget([
                'model' => $model,
                'attribute' => 'is_self',
                'data' => ['' => '全部', '0' => '否', '1' => '是'],
                'options' => ['placeholder' => $model->getAttributeLabel('is_self')],
                'pluginOptions' => ['allowClear' => true],
                'pluginEvents' => ['change' => 'function(){ submitForm()}']
            ])
            ?>
        </div>
    </div>

    <!-- 时间 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown" style="width:225px;">
            <?php
            echo DateRangePicker::widget([
                'model' => $model,
                'attribute' => 'date_range',
                'value' => '',
                'convertFormat' => true,
                'initRangeExpr' => true,
                'presetDropdown' => true,
                'pluginOptions' => [
                    'timePickerIncrement' => 15,
                    'locale' => ['format' => 'Y-m-d'],
                    'showDropdowns' => true,
                    'opens' => 'left',
                    'ranges' => [
                        Yii::t('app', "Today") => ["moment().startOf('day')", "moment()"],
                        Yii::t('app', "Yesterday") => ["moment().startOf('day').subtract(1,'days')", "moment().endOf('day').subtract(1,'days')"],
                        I18NUitl::t('app', "{Lately} {n} {Day}", ['n' => 7]) => ["moment().startOf('day').subtract(6, 'days')", "moment()"],
                        I18NUitl::t('app', "{Lately} {n} {Day}", ['n' => 30]) => ["moment().startOf('day').subtract(29, 'days')", "moment()"],
                        Yii::t('app', "This Month") => ["moment().startOf('month')", "moment().endOf('month')"],
                        Yii::t('app', "Last Month") => ["moment().subtract(1, 'month').startOf('month')", "moment().subtract(1, 'month').endOf('month')"],
                    ],
                ],
                'pluginEvents' => [
                    "apply.daterangepicker" => "function() { submitForm(); }",
                ],
            ]);
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
