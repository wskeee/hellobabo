<?php

use common\models\goods\Goods;
use common\models\goods\searchs\SceneGroupSearch;
use common\utils\I18NUitl;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model SceneGroupSearch */
/* @var $form ActiveForm */
?>

<div class="scene-group-search pull-right">

    <?php $form = ActiveForm::begin([
        'id' => 'search-form',
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <!-- 名称 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown">
            <?=
            Html::activeTextInput($model, 'name', [
                'class' => 'form-control',
                'placeholder' => I18NUitl::t('app', 'Name'),
                'onBlur' => 'submitForm()',
            ])
            ?>
        </div>
    </div>
    
    <!-- 所属商品 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown" style="width:150px">
            <?=
            Select2::widget([
                'model' => $model,
                'attribute' => 'relate_id',
                'data' => ArrayHelper::map(Goods::find()->all(), 'id', 'goods_name'),
                'options' => ['placeholder' => Yii::t('app', 'Goods')],
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
