<?php

use common\models\goods\GoodsMaterial;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model GoodsMaterial */
/* @var $form ActiveForm */
?>

<div class="goods-material-form">

    <?php
    $form = ActiveForm::begin([
                'options' => ['class' => 'form form-horizontal',],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-sm-9\" style=\"line-height: 40px;\" >{input}</div>\n<div class=\"col-sm-9\">{error}</div>",
                    'labelOptions' => [
                        'class' => 'col-sm-1  control-label form-label',
                    ],
                ],
    ]);
    ?>

    <!-- 商品ID -->
    <?= Html::activeHiddenInput($model, 'goods_id') ?>

    <!--名称-->
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <!--输入类型-->
    <?=
    $form->field($model, 'input_type')->widget(Select2::class, [
        'data' => GoodsMaterial::$inputTypeMap,
        'hideSearch' => true,
        'options' => ['placeholder' => Yii::t('app', 'All')],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ])
    ?>

    <!--是否必选-->
    <?= $form->field($model, 'is_required')->checkbox(['value' => 1], false) ?>

    <!-- 描述 -->
    <?= $form->field($model, 'des')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <label class="col-sm-1 form-label"></label>
        <div class="col-sm-9"><?= Html::submitButton(Yii::t('app', 'Confirm'), ['class' => 'btn btn-success btn-flat']) ?></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
