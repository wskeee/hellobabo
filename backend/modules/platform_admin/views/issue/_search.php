<?php

use common\models\AdminUser;
use common\models\platform\Issue;
use common\models\platform\searchs\IssueSearch;
use common\utils\I18NUitl;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model IssueSearch */
/* @var $form ActiveForm */
?>

<div class="issue-search pull-right">

    <?php
    $form = ActiveForm::begin([
                'id' => 'search-form',
                'action' => ['index'],
                'method' => 'get',
    ]);
    ?>

    <!-- 类型 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown" style="width:100px">
            <?=
            Select2::widget([
                'model' => $model,
                'attribute' => 'type',
                'data' => Issue::$typeNameMap,
                'options' => ['placeholder' => Yii::t('app', 'Type')],
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

    <!-- 反馈人 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown">
            <?=
            Html::activeTextInput($model, 'contact_name', [
                'class' => 'form-control',
                'placeholder' => I18NUitl::t('app', 'Issue By'),
                'onBlur' => 'submitForm()',])
            ?>
        </div>
    </div>

    <!-- 处理状态 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown" style="width:80px">
            <?=
            Select2::widget([
                'model' => $model,
                'attribute' => 'status',
                'data' => Issue::$statusNameMap,
                'options' => ['placeholder' => Yii::t('app', 'Status')],
                'pluginOptions' => ['allowClear' => true],
                'pluginEvents' => ['change' => 'function(){ submitForm()}']
            ])
            ?>
        </div>
    </div>

    <!-- 处理结果 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown" style="width:80px">
            <?=
            Select2::widget([
                'model' => $model,
                'attribute' => 'result',
                'data' => Issue::$resultNameMap,
                'options' => ['placeholder' => Yii::t('app', 'Result')],
                'pluginOptions' => ['allowClear' => true],
                'pluginEvents' => ['change' => 'function(){ submitForm()}']
            ])
            ?>
        </div>
    </div>

    <!-- 处理人 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown" style="width:120px">
            <?=
            Select2::widget([
                'model' => $model,
                'attribute' => 'handled_by',
                'data' => AdminUser::getUserByType(AdminUser::TYPE_GENERAL),
                'options' => ['placeholder' => Yii::t('app', 'Handled By')],
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