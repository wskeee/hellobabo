<?php

use common\models\goods\GoodsScene;
use common\models\goods\SceneGroup;
use common\widgets\webuploader\ImagePicker;
use kartik\select2\Select2 as Select22;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model GoodsScene */
/* @var $form ActiveForm */
?>

<div class="goods-scene-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= Html::activeHiddenInput($model, 'goods_id') ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?=
    $form->field($model, 'group_id')->widget(Select2::class, [
        'data' => SceneGroup::getGroup($model->goods_id),
        'options' => ['placeholder' => Yii::t('app', 'Select Placeholder')],
    ])
    ?>
    <!-- 效果图、参考图、源图 -->
    <div style="display: inline-block;margin-right: 20px;">
        <?= $form->field($model, 'effect_url')->widget(ImagePicker::class) ?>
    </div>
    <div style="display: none;margin-right: 20px;">
        <?= $form->field($model, 'demo_url')->widget(ImagePicker::class) ?>
    </div>
    <div style="display: inline-block;margin-right: 20px;">
        <?= $form->field($model, 'source_url')->widget(ImagePicker::class) ?>
    </div>

    <br/>

    <!-- 默认选择、必须上图、不可更改 -->
    <div style="display: inline-block;margin-right: 20px;">
        <?= $form->field($model, 'is_selected')->checkbox() ?>
    </div>
    <div style="display: inline-block;margin-right: 20px;">
        <?= $form->field($model, 'immutable')->checkbox()?>
    </div>
    <span style="color: #ccc">（注意：不可选为true时，位置只能为【开头】或者【结尾】）</span>
    
    <?=
    $form->field($model, 'pos')->widget(Select22::class, [
        'data' => GoodsScene::$posNameMap
    ])
    ?>

        <?= $form->field($model, 'des')->textarea(['maxlength' => true]) ?>

    <div class="form-group">
    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>
