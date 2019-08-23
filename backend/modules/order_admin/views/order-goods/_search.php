<?php

use common\models\order\OrderGoods;
use common\models\order\searchs\OrderGoodsSearch;
use common\utils\I18NUitl;
use kartik\daterange\DateRangePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model OrderGoodsSearch */
/* @var $form ActiveForm */
?>

<div class="workflow-print-search pull-right">

    <?php
    $form = ActiveForm::begin([
                'id' => 'search-form',
                'action' => ['index'],
                'method' => 'get',
    ]);
    ?>

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

    <!-- 绘本名称 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown" style="width:150px">
            <?=
            Html::activeTextInput($model, 'goods_name', [
                'class' => 'form-control',
                'placeholder' => I18NUitl::t('app', '{Goods}{Name}'),
                'onBlur' => 'submitForm()',
            ]);
            ?>
        </div>
    </div>

    <!-- 状态 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown" style="width:160px">
            <?=
            Select2::widget([
                'model' => $model,
                'attribute' => 'status',
                'data' => [
                    OrderGoods::STATUS_UPLOAD_PIC_CHECK => OrderGoods::$statusNameMap[OrderGoods::STATUS_UPLOAD_PIC_CHECK],
                    OrderGoods::STATUS_DESIGN_CHECK => OrderGoods::$statusNameMap[OrderGoods::STATUS_DESIGN_CHECK],
                    OrderGoods::STATUS_PRINT_CHECK => OrderGoods::$statusNameMap[OrderGoods::STATUS_PRINT_CHECK],
                ],
                'options' => ['placeholder' => Yii::t('app', 'Status')],
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
