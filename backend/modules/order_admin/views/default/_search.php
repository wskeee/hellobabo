<?php

use common\models\goods\Goods;
use common\models\order\Order;
use common\models\order\searchs\OrderSearch;
use common\utils\I18NUitl;
use kartik\daterange\DateRangePicker;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model OrderSearch */
/* @var $form ActiveForm */
?>

<div class="order-search pull-right">

    <?php $form = ActiveForm::begin([
        'id' => 'search-form',
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    
    <!-- SN -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown">
            <?=
            Html::activeTextInput($model, 'order_sn', [
                'class' => 'form-control',
                'placeholder' => I18NUitl::t('app', '{Order}{Sn}'),
                'onBlur' => 'submitForm()',
            ])
            ?>
        </div>
    </div>
    
    <!-- 收货人 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown" style="width:150px">
            <?=
            Html::activeTextInput($model, 'consignee', [
                'class' => 'form-control',
                'placeholder' => I18NUitl::t('app', 'Contacter'),
                'onBlur' => 'submitForm()',
            ]);
            ?>
        </div>
    </div>
    
    <!-- 状态 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown" style="width:120px">
            <?=
            Select2::widget([
                'model' => $model,
                'attribute' => 'order_status',
                'data' => Order::$orderStatusNameMap,
                'options' => ['placeholder' => Yii::t('app', 'Status')],
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