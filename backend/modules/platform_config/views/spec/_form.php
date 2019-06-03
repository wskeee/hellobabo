<?php

use common\models\goods\GoodsModel;
use common\models\goods\GoodsSpec;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model GoodsSpec */
/* @var $form ActiveForm */
?>

<div class="spec-form">

    <?php
    $form = ActiveForm::begin([
                'options' => [
                    'class' => 'form form-horizontal',
                ],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-sm-9\" style=\"line-height: 40px;\" >{input}</div>\n<div class=\"col-sm-9\">{error}</div>",
                    'labelOptions' => [
                        'class' => 'col-sm-1 control-label form-label',
                    ],
                ],
    ]);
    ?>

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

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sort_order')->textInput() ?>

    <?= $form->field($model, 'des')->textInput(['maxlength' => true]) ?>
    
    <div class="form-group">
        <label class="col-sm-1 form-label"></label>
        <div class="col-sm-9"><?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-flat']) ?></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
