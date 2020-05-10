<?php

use common\models\goods\Goods;
use common\models\goods\GoodsMaterialValue;
use common\models\goods\SceneGroup;
use common\models\goods\searchs\GoodsSceneSearch;
use common\utils\I18NUitl;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model GoodsSceneSearch */
/* @var $goodsModel Goods */
/* @var $form ActiveForm */
?>

<div class="goods-scene-search pull-right">

    <?php
    $form = ActiveForm::begin([
                'id' => 'search-form',
                'action' => ["index?goods_id={$goodsModel->id}"],
                'method' => 'get',
    ]);
    ?>

    <!-- 名称 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown" style="width:120px">
            <?=
            Html::activeTextInput($model, 'name', [
                'class' => 'form-control',
                'placeholder' => $model->getAttributeLabel('name'),
                'onBlur' => 'submitForm()',])
            ?>
        </div>
    </div>

    <!-- 类型 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown">
            <?=
            Select2::widget([
                'model' => $model,
                'attribute' => 'material_value_id',
                'data' => GoodsMaterialValue::getGoodsMaterialValue($model->goods_id, true),
                'options' => ['placeholder' => $model->getAttributeLabel('material_value_id')],
                'pluginOptions' => ['allowClear' => true],
                'pluginEvents' => ['change' => 'function(){ submitForm()}']
            ])
            ?>
        </div>
    </div>

    <!-- 是否为样例 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown" >
            <?=
            Select2::widget([
                'model' => $model,
                'attribute' => 'is_demo',
                'data' => ['否', '是'],
                'options' => ['placeholder' => $model->getAttributeLabel('is_demo')],
                'pluginOptions' => ['allowClear' => true],
                'pluginEvents' => ['change' => 'function(){ submitForm()}']
            ])
            ?>
        </div>
    </div>
    
    <!-- 已选 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown">
            <?=
            Select2::widget([
                'model' => $model,
                'attribute' => 'is_selected',
                'data' => ['否', '是'],
                'options' => ['placeholder' => $model->getAttributeLabel('is_selected')],
                'pluginOptions' => ['allowClear' => true],
                'pluginEvents' => ['change' => 'function(){ submitForm()}']
            ])
            ?>
        </div>
    </div>

    <!-- 用户可选 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown" >
            <?=
            Select2::widget([
                'model' => $model,
                'attribute' => 'immutable',
                'data' => ['否', '是'],
                'options' => ['placeholder' => $model->getAttributeLabel('immutable')],
                'pluginOptions' => ['allowClear' => true],
                'pluginEvents' => ['change' => 'function(){ submitForm()}']
            ])
            ?>
        </div>
    </div>

    <!-- 必需上图 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown">
            <?=
            Select2::widget([
                'model' => $model,
                'attribute' => 'page_is_required',
                'data' => ['否', '是'],
                'options' => ['placeholder' => $model->getAttributeLabel('page_is_required')],
                'pluginOptions' => ['allowClear' => true],
                'pluginEvents' => ['change' => 'function(){ submitForm()}']
            ])
            ?>
        </div>
    </div>

    

<?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">

    // 提交表单    
    function submitForm() {
        $('#search-form').submit();
    }
</script>