<?php

use common\widgets\webuploader\ImagePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\goods\GoodsCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="goods-category-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'goods-category-form',
            'class' => 'form form-horizontal',
//            'enctype' => 'multipart/form-data',
        ],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-7 col-md-7\">{input}</div>\n<div class=\"col-lg-7 col-md-7\">{error}</div>",
            'labelOptions' => [
                'class' => 'col-lg-1 col-md-1 control-label form-label',
            ],
        ],
    ]); ?>


    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><?= Html::encode($this->title) ?></h4>
            </div>

            <div class="modal-body">

                <!--类目名称-->
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                <!--类目编码-->
                <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

                <!-- 类目图片-->
                <?= $form->field($model, 'image')->widget(ImagePicker::class) ?>

            </div>

            <div class="modal-footer">

                <?= Html::submitButton(Yii::t('app', 'Confirm'), ['class' => 'btn btn-primary btn-flat']) ?>

            </div>

        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
