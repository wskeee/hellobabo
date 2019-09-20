<?php

use common\models\activity\searchs\VoteActivityHandSearch;
use common\models\activity\VoteActivityHand;
use common\utils\I18NUitl;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model VoteActivityHandSearch */
/* @var $form ActiveForm */
?>

<div class="vote-activity-hand-search pull-right">

    <?php
    $form = ActiveForm::begin([
                'id' => 'search-form',
                'action' => ['index'],
                'method' => 'get',
    ]);
    ?>
    
    <?= Html::activeHiddenInput($model, 'activity_id') ?>

    <!-- 名称 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown">
            <?=
            Html::activeTextInput($model, 'target_name', [
                'class' => 'form-control',
                'placeholder' => I18NUitl::t('app', 'Name'),
                'onBlur' => 'submitForm()',])
            ?>
        </div>
    </div>
    
    <!-- 处理结果 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown" style="width:150px">
            <?=
            Select2::widget([
                'model' => $model,
                'attribute' => 'check_status',
                'data' => VoteActivityHand::$checkStatusNameMap,
                'options' => ['placeholder' => Yii::t('app', '状态')],
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
