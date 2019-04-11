<?php

use common\models\goods\Goods;
use common\models\goods\GoodsAttribute;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $model Goods */
$goodsRefs = ArrayHelper::index($model->goodsAttValueRefs, 'attribute_value_id', 'attribute_id');;
foreach($goodsRefs as &$ref){
    $ref = array_keys($ref);
}
?>
<div class="goods-att-box">
    <?php $form = ActiveForm::begin(['id' => 'goods-attr-form']);?>
    
    <?php foreach ($model->goodsModel->goodsAttributes as $att): ?>
        <div class="att-item-box">
            <label class="att-label"><?= ($att->is_required ? "*" : '') . $att->name ?></label>
            <div class="att-value-box">
                <?php
                $attName = "attrs[$att->id][]";
                $attValues = ArrayHelper::map($att->goodsAttributeValues, 'id', 'value');
                $attValue = isset($goodsRefs[$att->id]) ? $goodsRefs[$att->id] : null;
                switch ($att->input_type) {
                    case GoodsAttribute::TYPE_SINGLE_SELECT:
                        echo Select2::widget([
                            'name' => $attName,
                            'value' => $attValue,
                            'data' => $attValues,
                        ]);
                        break;
                    case GoodsAttribute::TYPE_MULTPLE_SELECT:
                        echo Html::checkboxList($attName, $attValue, $attValues);
                        break;
                    case GoodsAttribute::TYPE_SINGLE_INPUT:
                        echo Html::textInput($attName, '', ['maxlength' => $att->value_length, 'class' => 'form-control']);
                        break;
                    case GoodsAttribute::TYPE_MULTPLE_INPUT:
                        echo Html::textarea($attName, '', ['maxlength' => $att->value_length, 'class' => 'form-control']);
                        break;
                }
                ?>
            </div>
            <div class="clean-padding"><div class="help-block"></div></div>
        </div>
    <?php endforeach; ?>
    
    <?php ActiveForm::end(); ?>
</div>
<script>
    /**
     * 保存属性
     */
    function saveAttribute() {
        $.ajax({
                type: "POST",
                url: 'save-attribute?goods_id=<?= $model->id ?>',
                data: $('#goods-attr-form').serialize(),
                success: function (r, textStatus) {
                    if (r.code != '0') {
                        console.log(r);
                        $.notify({message: '保存属性失败！\n' + r.msg}, {type: 'danger'});
                    }else{
                        $.notify({message: '保存属性成功！'}, {type: 'success'});
                    }
                },
                error: function (e) {
                    $.notify({message: '保存属性失败！'}, {type: 'danger'});
                }
            });
    }
</script>
