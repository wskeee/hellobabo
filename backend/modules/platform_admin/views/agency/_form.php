<?php

use common\models\AdminUser;
use common\models\platform\Agency;
use common\models\system\Region;
use common\widgets\webuploader\ImagePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\platform\Agency */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="agency-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'des')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cover_url')->widget(ImagePicker::class) ?>

    <?= $form->field($model, 'level')->widget(Select2::class,[
            'data' => Agency::$levelNames
    ]) ?>

    <!-- 地区选择 -->
    <div class="form-group">
        <div class="dep-dropdown-box">
            <p><label><?= Yii::t('app', 'Province') ?></label></p>
            <div class="dep-dropdown">
                <?=
                Select2::widget([
                    'model' => $model,
                    'attribute' => 'province',
                    'data' => Region::getRegionList(['level' => 1]),
                    'options' => ['placeholder' => Yii::t('app', 'Select Placeholder'), 'data-level' => 'region1',],
                    'pluginEvents' => ['change' => 'function(evt,manual){if(!manual)changeRegion($(evt.currentTarget),1) }']
                ])
                ?>
            </div>
        </div>
        <div class="dep-dropdown-box">
            <p><label><?= Yii::t('app', 'City') ?></label></p>
            <div class="dep-dropdown">
                <?=
                Select2::widget([
                    'model' => $model,
                    'attribute' => 'city',
                    'data' => Region::getRegionList(['parent_id' => empty($model->province) ? null : $model->province]),
                    'options' => ['placeholder' => Yii::t('app', 'Select Placeholder'), 'data-level' => 'region2',],
                    'pluginEvents' => ['change' => 'function(evt,manual){if(!manual)changeRegion($(evt.currentTarget),2)}']
                ])
                ?>
            </div>
        </div>
        <div class="dep-dropdown-box">
            <p><label><?= Yii::t('app', 'District') ?></label></p>
            <div class="dep-dropdown">
                <?=
                Select2::widget([
                    'id' => 'region2',
                    'model' => $model,
                    'attribute' => 'district',
                    'data' => Region::getRegionList(['parent_id' => empty($model->city) ? null : $model->city]),
                    'options' => ['placeholder' => Yii::t('app', 'Select Placeholder'), 'data-level' => 'region3',],
                    'pluginEvents' => ['change' => 'function(evt,manual){if(!manual)changeRegion($(evt.currentTarget),3)}']
                ])
                ?>
            </div>
        </div>
        <div class="dep-dropdown-box">
            <p><label><?= Yii::t('app', 'Town') ?></label></p>
            <div class="dep-dropdown">
                <?=
                Select2::widget([
                    'id' => 'region2',
                    'model' => $model,
                    'attribute' => 'town',
                    'data' => Region::getRegionList(['parent_id' => empty($model->district) ? null : $model->district]),
                    'options' => ['placeholder' => Yii::t('app', 'Select Placeholder'), 'data-level' => 'region4',],
                ])
                ?>
            </div>
        </div>
    </div>

    <p style="clear:both;"></p>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'admin_id')->widget(Select2::class,[
        'data' => AdminUser::getUserByType(AdminUser::TYPE_AGENCY)
    ]) ?>

    <?= $form->field($model, 'contacts_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contacts_phone')->textInput(['maxlength' => true]) ?>

    <?php //$form->field($model, 'idcard')->textInput(['maxlength' => true]) ?>

    <?php //$form->field($model, 'idcard_img_url')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'status')->textInput() ?>

    <?php // $form->field($model, 'apply_time')->textInput() ?>

    <?php // $form->field($model, 'check_id')->textInput() ?>

    <?php // $form->field($model, 'check_time')->textInput() ?>

    <?php // $form->field($model, 'check_feedback')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'is_del')->textInput() ?>

    <?php // $form->field($model, 'created_at')->textInput() ?>

    <?php // $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    /**
     * 地区列表变改
     * @param {jQuery} $dom
     * @param {int} level
     * @returns {void}
     */
    function changeRegion($select, level) {
        for (var i = level + 1; i <= 4; i++) {
            $('select[data-level=region' + i + ']').empty().val(null).trigger('change', {manual: true});
        }
        $.ajax({
            type: 'GET',
            url: '/system_admin/region/get-region?parent_id=' + $select.val()
        }).then(function (data) {
            $select_next = $('select[data-level=region' + (level + 1) + ']');
            $.each(data, function (id, name) {
                // create the option and append to Select2
                var option = new Option(name, id, false, false);
                $select_next.append(option);
            });
            $select_next.val(null).trigger('change', {manual: true});
        });
    }
</script>
