<?php

use common\models\goods\GoodsMaterialValue;
use common\utils\I18NUitl;
use common\widgets\webuploader\ImagePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\goods\GoodsScenePageMaterialItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="goods-scene-page-material-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="wskModalLabel"><?= I18NUitl::t('app', '{Add}{Material}') ?></h4>
            </div>

            <div class="modal-body" id="wskModalBody">
                <?= Html::activeHiddenInput($model, 'scene_page_id') ?>

                <?= $form->field($model, 'material_value_id')->widget(Select2::class, [
                    'data' => GoodsMaterialValue::getGoodsMaterialValue($model->scenePage->scene->goods_id, true),
                    'pluginEvents' => [
                        "change" => "function() { onMaterialValueChange() }",
                    ],
                    'options' => [
                        'id' => 'material_value_id',
                        'placeholder' => '请选择...'
                    ]
                ]) ?>

                <?= Html::activeHiddenInput($model, 'material_value_item_id', ['id' => 'material_value_item_id']) ?>

                <div id="item-box">

                </div>

            </div>

            <div class="modal-footer">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>

        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<style>
    #item-box {
        display: flex;
        justify-content: flex-start;
        align-items: flex-start;
        min-height: 300px;
        width: 100%;
    }

    #item-box .item {
        flex: 0.1;
        border: solid 1px #ddd;
        padding: 5px;
        height: 123px;
        margin-right: 10px;
    }

    #item-box .item img {
        height: 100%;
    }

    #item-box .selected {
        background: #f2f2f2;
    }
</style>

<script>
    var item_id = <?= $model->material_value_item_id ? $model->material_value_item_id : 0 ?>;
    $(function () {
        if ($('#material_value_id').val() != '') {
            onMaterialValueChange();
        }
    });

    /**
     * 获取素材项数据
     */
    function onMaterialValueChange() {
        var mvid = $('#material_value_id').val();
        $.get('get-material-item', {material_value_id: mvid}, function (r) {
            renderMaterialItem(r);
        });
    }

    /**
     * 渲染素材项
     * @param $items
     */
    function renderMaterialItem(items) {
        $('#item-box').empty();
        $.each(items, function (key, itemData) {
            var item_html = '<div class="item">' +
                '<img src=""/>' +
                '</div>';
            var item = $(item_html).appendTo($('#item-box'));
            item.attr('data-id', itemData.id).find('img').attr('src', itemData.effect_url);
            if (itemData.id == item_id) {
                item.addClass('selected');
            }
            item.on('click', function () {
                $('.item').removeClass('selected');
                $(this).addClass('selected');
                item_id = $(this).attr('data-id');
                $('#material_value_item_id').val(item_id);
            });
        });
    }
</script>
