<?php

use common\utils\I18NUitl;
use kartik\widgets\Select2;
use yii\helpers\BaseHtml;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\platform\searchs\CommentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="comment-search">

    <?php $form = ActiveForm::begin([
        'id' => 'search-form',
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <!-- 内容 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown">
            <?=
            Html::activeTextInput($model, 'content', [
                'class' => 'form-control',
                'placeholder' => I18NUitl::t('app', 'Content'),
                'onBlur' => 'submitForm()',
            ])
            ?>
        </div>
    </div>

    <!-- Is Del -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown">
            <?=
            Select2::widget([
                'model' => $model,
                'attribute' => 'is_del',
                'data' => ['0' => '否', '1' => '是'],
                'options' => ['placeholder' => '是否删除',],
                'pluginOptions' => ['allowClear' => true],
                'pluginEvents' => ['change' => 'function(){ submitForm()}']
            ])
            ?>
        </div>
    </div>

    <!-- time start -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown">
            <?=
            BaseHtml::textInput('start_time', date('Y-m-d', strtotime('today -7 day')), [
                'class' => 'form-control',
                'placeholder' => I18NUitl::t('app', '{Start} {Time}'),
                'onBlur' => 'submitForm()',
            ])
            ?>
        </div>
    </div>

    <!-- time end -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown">
            <?=
            BaseHtml::textInput('end_time', date('Y-m-d', time()), [
                'class' => 'form-control',
                'placeholder' => I18NUitl::t('app', '{End} {Time}'),
                'onBlur' => 'submitForm()',
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