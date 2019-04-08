<?php

use common\models\goods\Goods;
use common\models\goods\GoodsAttribute;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $model Goods */
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
                switch ($att->input_type) {
                    case GoodsAttribute::TYPE_SINGLE_SELECT:
                        echo Select2::widget([
                            'name' => $attName,
                            'value' => '',
                            'data' => $attValues,
                        ]);
                        break;
                    case GoodsAttribute::TYPE_MULTPLE_SELECT:
                        echo Html::checkboxList($attName, null, $attValues);
                        break;
                    case GoodsAttribute::TYPE_SINGLE_INPUT:
                        echo Html::textInput($attName, '11', ['maxlength' => $att->value_length, 'class' => 'form-control']);
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
     * 获取属性formdata
     * @returns {array}
     */
    function saveAttribute() {
        $.post('save-attribute?goods_id=<?= $model->id ?>',$('#goods-attr-form').serialize());
    }
</script>
