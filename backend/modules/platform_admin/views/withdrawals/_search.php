<?php

use common\models\AdminUser;
use common\models\platform\searchs\WithdrawalsSearch;
use common\models\platform\Withdrawals;
use common\utils\I18NUitl;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model WithdrawalsSearch */
/* @var $form ActiveForm */
?>

<div class="withdrawals-search pull-right">

    <?php $form = ActiveForm::begin([
        'id' => 'search-form',
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    
    <!-- SN -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown" style="width:120px">
            <?=
            Html::activeTextInput($model, 'order_sn', [
                'class' => 'form-control',
                'placeholder' => I18NUitl::t('app', 'SN'),
                'onBlur' => 'submitForm()',])
            ?>
        </div>
    </div>


    <!-- 审核人 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown" style="width:120px">
            <?=
            Select2::widget([
                'model' => $model,
                'attribute' => 'check_user_id',
                'data' => array_merge(['0' => '系统'], AdminUser::getUserByType(AdminUser::TYPE_GENERAL)),
                'options' => ['placeholder' => Yii::t('app', 'Verifier')],
                'pluginOptions' => ['allowClear' => true],
                'pluginEvents' => ['change' => 'function(){ submitForm()}']
            ])
            ?>
        </div>
    </div>
    
    <!-- 提现人 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown" style="width:120px">
            <?=
            Html::activeTextInput($model, 'pay_realname', [
                'class' => 'form-control',
                'placeholder' => Yii::t('app', 'Pay Realname'),
                'onBlur' => 'submitForm()',])
            ?>
        </div>
    </div>
    
    <!-- 处理状态 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown" style="width:120px">
            <?=
            Select2::widget([
                'model' => $model,
                'attribute' => 'status',
                'data' => Withdrawals::$statusNameMap,
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