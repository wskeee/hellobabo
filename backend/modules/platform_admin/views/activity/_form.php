<?php

use common\models\activity\Activity;
use common\widgets\webuploader\ImagePicker;
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\activity\Activity */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="activity-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'type')->widget(Select2::class,['data' => Activity::$typeNames]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cover_url')->widget(ImagePicker::class) ?>

    <?= $form->field($model, 'share_poster_url')->widget(ImagePicker::class) ?>

    <!-- 商品展示图片 -->
    <div class="from-group tile-group">
        <div class="group-item">
            <label class="control-label form-label">
                <?= $model->getAttributeLabel('show_urls') ?>
            </label>
            <div>
                <?= Html::hiddenInput('Activity[show_urls][]') ?>
                <?= ImagePicker::widget(['name' => 'Activity[show_urls][]', 'value' => $model->show_urls, 'pluginOptions' => ['fileNumLimit' => 10]]) ?>
            </div>
        </div>
    </div>

    <?php /*= $form->field($model, 'content')->textarea(['rows' => 6]) */?>

    <?= $form->field($model, 'start_time')->widget(DatePicker::class,[
        'pluginOptions' => [
            'convertFormat' => true,
            'autoclose' => true,
            'todayHighlight' => true,
            'format' => 'yyyy-mm-dd',
        ],
    ])?>

    <?= $form->field($model, 'end_time')->widget(DatePicker::class,[
        'pluginOptions' => [
            'convertFormat' => true,
            'autoclose' => true,
            'todayHighlight' => true,
            'format' => 'yyyy-mm-dd',
        ],
    ]) ?>

    <?= $form->field($model, 'setting')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
