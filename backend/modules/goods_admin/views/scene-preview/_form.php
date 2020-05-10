<?php

use common\models\goods\GoodsMaterialValue;
use common\models\goods\GoodsScene;
use common\utils\I18NUitl;
use common\widgets\webuploader\ImagePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\goods\GoodsScenePreview */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="goods-scene-preview-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="wskModalLabel"><?= I18NUitl::t('app','{Add}{Scene}{Preview}') ?></h4>
            </div>

            <div class="modal-body" id="wskModalBody">
                <?= Html::activeHiddenInput($model, 'goods_id') ?>
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                <p>
                    <lable>场景</lable>
                    <?=
                    Select2::widget([
                        'model' => $model,
                        'attribute' => 'scene_id',
                        'data' => GoodsScene::getGoodsScene($model->goods_id),
                        'options' => [
                                'placeholder' => I18NUitl::t('app', '{Goods}{Scene}'),
                                'onChange' => 'onSceneChanged(this)',
                        ],
                        'pluginOptions' => ['allowClear' => true],
                    ])
                    ?>
                </p>
                <p>
                    <lable>素材</lable>
                    <?=
                    Select2::widget([
                        'model' => $model,
                        'attribute' => 'material_value_id',
                        'data' => GoodsMaterialValue::getGoodsMaterialValue($model->goods_id, true),
                        'options' => ['placeholder' => Yii::t('app', 'Material')],
                        'pluginOptions' => ['allowClear' => true],
                    ])
                    ?>
                </p>

                <?= $form->field($model, 'effect_url')->widget(ImagePicker::class) ?>

            </div>

            <div class="modal-footer">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>

        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    /**
     * 场景改变时
     * @param dom
     */
    function onSceneChanged(dom) {
        console.log(dom);
    }
</script>
