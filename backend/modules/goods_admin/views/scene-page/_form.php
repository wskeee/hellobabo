<?php

use common\models\goods\GoodsScenePage;
use common\models\goods\ShootingAngle;
use common\models\goods\ShootingFace;
use common\widgets\webuploader\FilePicker;
use common\widgets\webuploader\ImagePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model GoodsScenePage */
/* @var $form ActiveForm */
?>

<div class="goods-scene-page-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= Html::activeHiddenInput($model, 'scene_id') ?>

    <!-- 名称 -->
    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Optional')]) ?>

    <!-- 效果图 -->
    <?= $form->field($model, 'effect_url')->widget(ImagePicker::class) ?>

    <!-- 源始ID -->
    <?= Html::activeHiddenInput($model, 'source_id') ?>

    <!-- 源始模板 -->
    <?=
    $form->field($model, 'source_url')->widget(FilePicker::class, [
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
    ])
    ?>

    <!-- 位置 -->
    <?=
    $form->field($model, 'pos')->widget(Select2::class, [
        'data' => GoodsScenePage::$posNameMap,
        'options' => ['placeholder' => Yii::t('app', 'Select Placeholder')],
    ])
    ?>

    <?= $form->field($model, 'is_required')->checkbox() ?>

    <?= $form->field($model, 'des')->textInput(['maxlength' => true]) ?>

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
