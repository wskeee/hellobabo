<?php

use common\models\order\WorkflowDelivery;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model WorkflowDelivery */
/* @var $form ActiveForm */
?>

<div class="workflow-delivery-form">

    <?php $form = ActiveForm::begin(); ?>

    <?=
    $form->field($model, 'send_type')->widget(Select2::class, [
        'data' => WorkflowDelivery::$sendTypeNameMap,
        'options' => ['placeholder' => Yii::t('app', 'All')],
    ])
    ?>

    <?= Html::activeHiddenInput($model, 'shipping_code') ?>
    
    <?= Html::activeHiddenInput($model, 'shipping_name') ?>
    <div class="form-group" id='shipping-name'>
        <label class="control-label"><?= $model->getAttributeLabel('shipping_name') ?></label> 
        <div class="form-control disabled"><?= $model->shipping_name == "" ? "自动匹配" : $model->shipping_name ?></div>
    </div>
    

    <?= $form->field($model, 'shipping_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'invoice_no')->textInput(['maxlength' => true, 'onblur' => 'queryExpress($(this).val())']) ?>

    <div id='info' style="color: #aaa;margin: 10px 0;">测试请输入：472141765618</div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    /**
     * 查询快递
     * 
     * @param {string} $sn
     * @returns {string}
     */
    function queryExpress($sn) {
        $info = $('#info');
        $info.html('查询中...');
    
        $.get('query-express', {'sn': $sn}, function (r) {
            var data = r.data;
            var expressData = data.data;
            if (data['ret'] == 200) {
                $('#workflowdelivery-shipping_code').val(expressData['comid']);
                $('#workflowdelivery-shipping_name').val(expressData['com']);
                $('#shipping-name .form-control').html(expressData['com']);
                $info.html('查询成功!');
            } else {
                $info.html('查询失败，'+data['msg']);
            }
        });
    }
</script>