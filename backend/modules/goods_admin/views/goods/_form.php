<?php

use backend\modules\goods_admin\assets\GoodsModuleAsset;
use common\models\AdminUser;
use common\models\goods\Goods;
use common\models\goods\GoodsCategory;
use common\models\goods\GoodsDetail;
use common\widgets\webuploader\ImagePicker;
use common\widgets\webuploader\VideoPicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Goods */
/* @var $goodsDetails GoodsDetail */
/* @var $form ActiveForm */

GoodsModuleAsset::register($this);
$goodsDetails = $model->isNewRecord ? new GoodsDetail() : $model->goodsDetails;
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

    <!-- 类型 -->
    <?= $form->field($model, 'type')->widget(Select2::class, [
        'data' => Goods::$typeKeyMap,
        'options' => ['placeholder' => Yii::t('app', 'Select Placeholder')],
    ]) ?>

    <!-- 显示方式 -->
    <?= $form->field($model, 'orientation')->widget(Select2::class, [
        'data' => Goods::$orientationNames,
    ]) ?>
    
    <!-- 商品名称 -->
    <?= $form->field($model, 'goods_name')->textInput(['maxlength' => true]) ?>

    <!-- 商品英文名 -->
    <?= $form->field($model, 'goods_english_name')->textInput(['maxlength' => true]) ?>

    <!-- 商品标题 -->
    <?= $form->field($model, 'goods_title')->textInput(['maxlength' => true]) ?>     
    
    <!-- 商品参数 -->
    <?= $form->field($model, 'params')->textarea(['maxlength' => true]) ?>

    <!-- 标题图片 -->
    <div class="form-group picker-form-group">
        <label class="control-label form-label">标题图片</label>
        <div class="item">
            <?= ImagePicker::widget(['model' => $model, 'attribute' => 'goods_title_url']) ?>
        </div>
    </div>

    <div class="form-group picker-form-group">
        <label class="control-label form-label">图片/视频</label>
        <!-- 封面 -->
        <div class="item">
            <label><?= $model->getAttributeLabel('cover_url') ?></label>
            <?= ImagePicker::widget(['model' => $model, 'attribute' => 'cover_url']) ?>
        </div>
        <!-- 视频 -->
        <div class="item">
            <label><?= $model->getAttributeLabel('video_url') ?></label>
            <?= VideoPicker::widget(['model' => $model, 'attribute' => 'video_url']) ?>
        </div>
        <!-- 分享图 -->
        <div class="item">
            <label><?= $model->getAttributeLabel('poster_url') ?></label>
            <?= ImagePicker::widget(['model' => $model, 'attribute' => 'poster_url']) ?>
        </div>
        <!-- 海报图 -->
        <div class="item">
            <label><?= $model->getAttributeLabel('share_thumb_url') ?></label>
            <?= ImagePicker::widget(['model' => $model, 'attribute' => 'share_thumb_url']) ?>
        </div>
    </div>

    <!-- 商品展示图片 -->
    <div class="from-group tile-group">
        <div class="group-item">
            <label class="control-label form-label">
                <?= $model->getAttributeLabel('show_urls') ?>
            </label>
            <div>
                <?= Html::hiddenInput('Goods[show_urls][]') ?>
                <?= ImagePicker::widget(['name' => 'Goods[show_urls][]', 'value' => $model->show_urls, 'pluginOptions' => ['fileNumLimit' => 5]]) ?>
            </div>
        </div>
    </div>

    <!-- 设置商品是否需要初始 -->
    <?php $form->field($model, 'init_required')->checkbox(['title' => '开启后在上传相片前需要初始绘本', 'style' => 'margin: 13px 0;'], false) ?>
    
    <!-- 商品佣金 -->
    <?= $form->field($model, 'commission')->textInput(['maxlength' => true, 'placeholder' => '请输入分享佣金']) ?>

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
    <?php //$form->field($model->isNewRecord ? new GoodsDetail() : $model->goodsDetails, 'content')->widget(UEDitor::class) ?>

    <!-- 移动商品详情 -->
    <div class="from-group tile-group">
        <div class="group-item">
            <label class="control-label form-label">
                <?= $goodsDetails->getAttributeLabel('mobile_content') ?>
            </label>
            <div>
                <?= Html::hiddenInput('GoodsDetail[mobile_content][]') ?>
                <?= ImagePicker::widget(['name' => 'GoodsDetail[mobile_content][]', 'value' => $goodsDetails->mobile_content, 'pluginOptions' => ['fileNumLimit' => 20]]) ?>
            </div>
        </div>
    </div>

    <!-- 移动商品购买详情 -->
    <div class="from-group tile-group">
        <div class="group-item">
            <label class="control-label form-label">
                <?= $goodsDetails->getAttributeLabel('mobile_buy_content') ?>
            </label>
            <div>
                <?= Html::hiddenInput('GoodsDetail[mobile_buy_content][]') ?>
                <?= ImagePicker::widget(['name' => 'GoodsDetail[mobile_buy_content][]', 'value' => $goodsDetails->mobile_buy_content, 'pluginOptions' => ['fileNumLimit' => 20]]) ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-1 control-label form-label"></label>
        <div class="col-sm-9">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
