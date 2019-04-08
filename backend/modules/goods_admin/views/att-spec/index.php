<?php

use backend\modules\goods_admin\assets\GoodsModuleAsset;
use common\models\goods\Goods;
use common\models\goods\GoodsModel;
use kartik\select2\Select2;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Goods */
GoodsModuleAsset::register($this);
?>
<div class="goods-att-spec-index">
    <!-- 模型选择 -->
    <div class="wsk-panel">
        <div class="title"><?= Yii::t('app', 'Model') ?></div>
        <div class="body">
            <?php
            $form = ActiveForm::begin([
                        'id' => 'goods-model-form',
                        'action' => ['change-model' , 'goods_id' => $model->id],
            ]);
            ?>
            
            <?=
            $form->field($model, 'model_id')->widget(Select2::class, [
                'data' => GoodsModel::getModels(),
                'options' => ['placeholder' => Yii::t('app', 'Select Placeholder')],
                'pluginEvents' => ['change' => 'function(){ $("#goods-model-form").submit() }']
            ])->label('')
            ?>
            
            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <!-- 属性 -->
    <div class="wsk-panel">
        <div class="title"><?= Yii::t('app', 'Attribute') ?></div>
        <div class="body">
            <?= $this->render('_att',['model' => $model]) ?>
        </div>
    </div>

    <!-- 规格 -->
    <div class="wsk-panel">
        <div class="title"><?= Yii::t('app', 'Spec') ?></div>
        <div class="body">
            <?= $this->render('_spec',['model' => $model]) ?>
        </div>
    </div>
    
    <a class="btn btn-success" onclick="saveF()"><?= Yii::t('app', 'Save') ?></a>
</div>
<script>
    function saveF(){
        //保存属性
        saveAttribute();
    }
</script>