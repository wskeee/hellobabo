<?php

use common\models\goods\GoodsSceneMaterial;
use common\models\goods\GoodsMaterialValue;
use common\models\goods\GoodsScene;
use common\models\goods\GoodsScenePage;
use common\models\goods\SceneGroup;
use common\widgets\webuploader\FilePicker;
use common\widgets\webuploader\ImagePicker;
use kartik\select2\Select2 as Select22;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model GoodsScene */
/* @var $scene_material_model GoodsSceneMaterial */
/* @var $page_model GoodsScenePage */
/* @var $form ActiveForm */
?>

<div class="goods-scene-form">

    <div class="alert alert-danger" role="alert">
        1、场景所有内容只显示于场景选择页<br/>
        2、场景页内容显示于图片上传页
    </div>

    <?php $form = ActiveForm::begin(); ?>

    <?= Html::activeHiddenInput($model, 'goods_id') ?>
    <?= Html::activeHiddenInput($scene_material_model, 'id') ?>
    <?= Html::activeHiddenInput($page_model, 'id') ?>
    <!-- 源始ID -->
    <?= Html::activeHiddenInput($page_model, 'source_id') ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => '显示于场景选择页的场景名称'])->label('场景名称') ?>

    <?= $form->field($model, 'des')->textInput(['maxlength' => true, 'placeholder' => '对场景的简单描述'])->label('场景描述') ?>

    <!-- 场景页 -->
    <?php $form->field($page_model, 'name')->textInput(['maxlength' => true, 'placeholder' => '上传图片显示名称'])->label('场景页名称') ?>

    <?php $form->field($page_model, 'des')->textInput(['maxlength' => true, 'placeholder' => '场景页描述'])->label('场景页描述') ?>

    <!-- 关联素材 -->
    <?=
    $form->field($scene_material_model, 'material_value_id')->widget(Select2::class, [
        'data' => GoodsMaterialValue::getGoodsMaterialValue($model->goods_id, true),
        'options' => ['placeholder' => Yii::t('app', 'Material')],
        'pluginOptions' => ['allowClear' => true],
    ])
    ?>

    <!-- 效果图、参考图、源图 -->
    <div style="display: inline-block;margin-right: 20px;">
        <?= $form->field($model, 'effect_url')->widget(ImagePicker::class)->label('场景选择效果图') ?>
    </div>

    <!-- 场景页效果图 -->
    <div style="display: inline-block;margin-right: 20px;">
        <?= $form->field($page_model, 'effect_url')->widget(ImagePicker::class)->label('上传页面效果图') ?>
    </div>
    <!-- 场景页场景文件 -->
    <!-- 源始模板 -->
    <div style="display: inline-block;margin-right: 20px;">
        <?=
        $form->field($page_model, 'source_url')->widget(FilePicker::class, [
            'pluginOptions' => [
                'formData' => [
                    'is_adobe' => 1,
                ],
                'accept' => [
                    'extensions' => 'zip',
                    'mimeTypes' => 'application/zip',
                ]
            ],
            'pluginEvents' => [
                "fileDequeued" => "fileDequeued",
                'uploadComplete' => "uploadComplete",
            ]
        ])->label('场景页Zip文件')
        ?>
    </div>
    <!--<div style="display: none;margin-right: 20px;">
        <? /*= $form->field($model, 'demo_url')->widget(ImagePicker::class) */ ?>
    </div>
    <div style="display: inline-block;margin-right: 20px;">
        <? /*= $form->field($model, 'source_url')->widget(ImagePicker::class) */ ?>
    </div>-->

    <br/>

    <!-- 默认选择、必须上图、不可更改 -->
    <div style="display: inline-block;margin-right: 20px;">
        <?= $form->field($model, 'is_demo')->checkbox() ?>
    </div>
    <div style="display: inline-block;margin-right: 20px;">
        <?= $form->field($model, 'is_selected')->checkbox() ?>
    </div>
    <div style="display: inline-block;margin-right: 20px;">
        <?= $form->field($model, 'immutable')->checkbox() ?>
    </div>
    <div style="display: inline-block;margin-right: 20px;">
        <?= $form->field($page_model, 'is_required')->checkbox() ?>
    </div>
    <span style="color: #aaa">（注意：设置为不可选时，用户无法看到（选择）该场景）</span>

    <?php
    /*
    $form->field($model, 'pos')->widget(Select2::class, [
        'data' => GoodsScene::$posNameMap
    ]);
    */
    ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    /**
     * 清除源文件
     *
     * @param {type} evt
     * @param {type} file
     * @returns {undefined}
     */
    function fileDequeued(evt,file){
        $source_id = $('#goodsscenepage-source_id');
        $source_id.val("");
    }

    /**
     * 源文件上传完成
     *
     * @param {type} evt
     * @param {type} dbFile
     * @param {type} file
     * @returns {undefined}
     */
    function uploadComplete(evt,dbFile,file){
        $source_id = $('#goodsscenepage-source_id');
        $source_id.val(dbFile.metadata.adobe_id);
    }
</script>