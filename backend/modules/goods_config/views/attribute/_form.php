<?php

use common\models\goods\GoodsAttribute;
use common\models\goods\GoodsModel;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model GoodsAttribute */
/* @var $form ActiveForm */
?>

<div class="goods-attribute-form">

    <?php
    $form = ActiveForm::begin([
                'options' => [
                    'id' => 'goods-attribute-form',
                    'class' => 'form form-horizontal',
//            'enctype' => 'multipart/form-data',
                ],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-sm-9\" style=\"line-height: 40px;\" >{input}</div>\n<div class=\"col-sm-9\">{error}</div>",
                    'labelOptions' => [
                        'class' => 'col-sm-1  control-label form-label',
                    ],
                ],
    ]);
    ?>

    <!--名称-->
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <!--所属模型-->
    <?=
    $form->field($model, 'model_id')->widget(Select2::class, [
        'data' => GoodsModel::getModels(),
        'hideSearch' => true,
        'options' => ['placeholder' => Yii::t('app', 'All')],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ])
    ?>

    <!--输入类型-->
    <?=
    $form->field($model, 'input_type')->widget(Select2::class, [
        'data' => GoodsAttribute::$inputTypeMap,
        'hideSearch' => true,
        'options' => ['placeholder' => Yii::t('app', 'All')],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ])
    ?>

    <!--值长度-->
    <?= $form->field($model, 'value_length')->textInput(['type' => 'number']) ?>

    <!--是否必选-->
    <?= $form->field($model, 'is_required')->checkbox(['value' => 1], false) ?>

    <!--是否搜索-->
    <?= $form->field($model, 'index_type')->checkbox(['value' => 1], false) ?>

    <div class="form-group">
        <label class="col-sm-1 form-label"></label>
        <div class="col-sm-9"><?= Html::submitButton(Yii::t('app', 'Confirm'), ['class' => 'btn btn-success btn-flat']) ?></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
