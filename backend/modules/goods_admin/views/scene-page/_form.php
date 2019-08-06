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

    <!-- 拍摄角度 -->
    <?=
    $form->field($model, 'angle_id')->widget(Select2::class, [
        'data' => ShootingAngle::getValues(),
        'options' => ['placeholder' => Yii::t('app', 'Select Placeholder')],
    ])
    ?>

    <!-- 拍摄表情 -->
    <?=
    $form->field($model, 'face_id')->widget(Select2::class, [
        'data' => ShootingFace::getValues(),
        'options' => ['placeholder' => Yii::t('app', 'Select Placeholder')],
    ])
    ?>

    <!-- 效果图 -->
    <?= $form->field($model, 'effect_url')->widget(ImagePicker::class) ?>

    <!-- 源始ID -->
    <?= $form->field($model, 'source_id')->textInput(['maxlength' => true]) ?>

    <!-- 源始模板 -->
    <?=
    $form->field($model, 'source_url')->widget(FilePicker::class, ['pluginOptions' => [
            'accept' => [
                'extensions' => 'zip',
                'mimeTypes' => 'application/zip',
            ]
    ]])
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
