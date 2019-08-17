<?php

use common\models\goods\Goods;
use common\models\goods\SceneGroup;
use common\models\goods\searchs\GoodsSceneSearch;
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
                'placeholder' => Yii::t('app', 'Name'),
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
                'attribute' => 'group_id',
                'data' => SceneGroup::getGroup(),
                'options' => ['placeholder' => Yii::t('app', 'Type')],
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
                'options' => ['placeholder' => Yii::t('app', 'Is Selected')],
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
                'options' => ['placeholder' => Yii::t('app', 'Immutable')],
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