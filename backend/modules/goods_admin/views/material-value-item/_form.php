<?php

use common\widgets\webuploader\FilePicker;
use common\widgets\webuploader\ImagePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\goods\GoodsMaterialValueItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="goods-material-value-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= Html::activeHiddenInput($model, 'material_value_id') ?>

    <!-- 名称 -->
    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', '填写场景名称')]) ?>

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
        $source_id = $('#goodsmaterialvalueitem-source_id');
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
        $source_id = $('#goodsmaterialvalueitem-source_id');
        $source_id.val(dbFile.metadata.adobe_id);
    }
</script>