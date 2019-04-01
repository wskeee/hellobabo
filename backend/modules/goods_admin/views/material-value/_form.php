<?php

use common\models\goods\GoodsMaterialValue;
use common\utils\I18NUitl;
use common\widgets\webuploader\ImagePicker;
use common\widgets\webuploader\VideoPicker;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model GoodsMaterialValue */
/* @var $form ActiveForm */
?>

<div class="goods-material-value-form">
    
    <?php $form = ActiveForm::begin(); ?>

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="wskModalLabel"><?= I18NUitl::t('app','{Add}{Material}') ?></h4>
            </div>
            
            <div class="modal-body" id="wskModalBody">
                <?= Html::activeHiddenInput($model, 'material_id') ?>

                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                <div style="display: inline-block;margin-right: 20px;">
                    <?= $form->field($model, 'effect_url')->widget(ImagePicker::class) ?>
                </div>
                <div style="display: inline-block;margin-right: 20px;">
                    <?= $form->field($model, 'source_url')->widget(ImagePicker::class) ?>
                </div>

                <?= $form->field($model, 'des')->textarea() ?>
            </div>
            
            <div class="modal-footer">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>
           
       </div>
    </div> 
    
    <?php ActiveForm::end(); ?>

</div>
