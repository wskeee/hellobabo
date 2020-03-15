<?php

use common\models\goods\Goods;
use common\models\goods\GoodsMaterialValue;
use common\models\goods\SceneGroup;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\goods\searchs\GoodsScenePreviewSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="goods-scene-preview-search">

    <?php $form = ActiveForm::begin([
        'id' => 'search-form',
        'action' => ['index' , 'goods_id' => $model->goods_id],
        'method' => 'get',
    ]); ?>

    <!-- 素材值 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown">
            <?=
            Select2::widget([
                'model' => $model,
                'attribute' => 'material_value_id',
                'data' => GoodsMaterialValue::getGoodsMaterialValue($model->goods_id, true),
                'options' => ['placeholder' => Yii::t('app', 'Material')],
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
