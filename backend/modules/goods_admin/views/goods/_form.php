<?php

use common\models\AdminUser;
use common\models\goods\Goods;
use common\models\goods\GoodsCategory;
use common\models\goods\GoodsDetail;
use common\widgets\ueditor\UEDitor;
use common\widgets\webuploader\ImagePicker;
use common\widgets\webuploader\VideoPicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Goods */
/* @var $form ActiveForm */
?>

<div class="goods-form">

    <?php
    $form = ActiveForm::begin([
                'options' => ['class' => 'form form-horizontal',],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-sm-9\" >{input}</div>\n<div class=\"col-sm-9\">{error}</div>",
                    'labelOptions' => ['class' => 'col-sm-1  control-label form-label',],
                ],
    ]);
    ?>

    <!-- 类目 -->
    <?=
    $form->field($model, 'category_id')->widget(Select2::class, [
        'data' => GoodsCategory::getCategory(),
        'options' => ['placeholder' => Yii::t('app', 'Select Placeholder')],
    ])
    ?>
    <!-- 商品名称 -->
    <?= $form->field($model, 'goods_name')->textInput(['maxlength' => true]) ?>

    <!-- 封面和视频 -->
    <div class="form-group">
        <label class="col-sm-1 control-label form-label">
            <?= $model->getAttributeLabel('cover_url') ?>
        </label>
        <div style="display: inline-block;padding-left: 15px;float: left;">
            <?= ImagePicker::widget(['model' => $model, 'attribute' => 'cover_url',]) ?>
        </div>
        <label class="col-sm-1 control-label form-label">
            <?= $model->getAttributeLabel('video_url') ?>
        </label>
        <div class="col-sm-2">
            <?= VideoPicker::widget(['model' => $model, 'attribute' => 'video_url',]) ?>
        </div>
    </div>

    <!-- 设置商品是否需要初始 -->
    <?= $form->field($model, 'init_required')->checkbox(['title' => '开启后在上传相片前需要初始绘本', 'style' => 'margin: 13px 0;'], false) ?>

    <!-- 作者 -->
    <?=
    $form->field($model, 'owner_id')->widget(Select2::class, [
        'data' => AdminUser::getUserByType(AdminUser::TYPE_OWNER),
        'options' => ['placeholder' => Yii::t('app', 'Select Placeholder')],
    ])
    ?>

    <!-- 商品标签 -->
    <?= $form->field($model, 'tags')->textInput(['maxlength' => true, 'placeholder' => '多个标签请用逗号隔开']) ?>

    <!-- 商品描述 -->
    <?= $form->field($model, 'goods_des')->textarea(['maxlength' => true, 'placeholder' => '简单描述商品']) ?>

    <!-- 商品详情 -->
    <?= $form->field($model->isNewRecord ? new GoodsDetail() : $model->goodsDetails, 'content')->widget(UEDitor::class) ?>

    <div class="form-group">
        <label class="col-sm-1 control-label form-label"></label>
        <div class="col-sm-9">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
