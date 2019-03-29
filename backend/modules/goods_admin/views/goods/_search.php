<?php

use common\models\goods\GoodsCategory;
use common\models\goods\searchs\GoodsSearch;
use common\utils\I18NUitl;
use kartik\widgets\Select2;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model GoodsSearch */
/* @var $form ActiveForm */

/* 其它选项下拉模板 */
$dep_template = "<div class=\"col-lg-12 col-md-12 clean-padding\">{input}</div>";
?>

<div class="goods-search wsk-search-panel">

    <?php
    $form = ActiveForm::begin([
                'id' => 'goods-search-form',
                'action' => ['index'],
                'method' => 'get',
                'options' => ['class' => 'form form-horizontal',],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-sm-9\" style=\"line-height: 40px;\" >{input}</div>\n<div class=\"col-sm-9\">{error}</div>",
                    'labelOptions' => ['class' => 'col-sm-1  control-label form-label',],
                ],
    ]);
    ?>

    <!-- 商品编号 -->
    <?= $form->field($model, 'goods_sn')->textInput([
        'maxlength' => true, 
        'placeholder' => I18NUitl::t('app', '{Please}{Input}{Goods}{SN}'),
        'onchange' => 'submitForm()',
        ]) ?>

    <!-- 类目 -->
    <?=
    $form->field($model, 'category_id')->widget(Select2::class, [
        'data' => GoodsCategory::getCategory(),
        'options' => ['placeholder' => Yii::t('app', 'All')],
        'pluginOptions' => ['allowClear' => true],
        'pluginEvents' => ['change' => 'function(){ submitForm()}']
    ])
    ?>

    <!-- 封其它选项 -->
    <div class="form-group">
        <label class="col-sm-1 control-label form-label">
            <?= I18NUitl::t('app', '{Other}{Option}') ?>
        </label>
        <div class="dep-dropdown-box col-sm-9">
            <div class="dep-dropdown">
                <?=
                $form->field($model, 'owner_id', ['template' => $dep_template,])->widget(Select2::class, [
                    'data' => GoodsCategory::getCategory(),
                    'options' => ['placeholder' => Yii::t('app', 'Owner')],
                    'pluginOptions' => ['allowClear' => true],
                    'pluginEvents' => ['change' => 'function(){ submitForm()}']
                ])
                ?>
            </div>
            <div class="dep-dropdown">
                <?=
                $form->field($model, 'created_by', ['template' => $dep_template,])->widget(Select2::class, [
                    'data' => GoodsCategory::getCategory(),
                    'options' => ['placeholder' => Yii::t('app', 'Created By')],
                    'pluginOptions' => ['allowClear' => true],
                    'pluginEvents' => ['change' => 'function(){ submitForm()}']
                ])
                ?>
            </div>
            <div class="dep-dropdown">
                <?=
                $form->field($model, 'status', ['template' => $dep_template,])->widget(Select2::class, [
                    'data' => GoodsCategory::getCategory(),
                    'options' => ['placeholder' => Yii::t('app', 'Status')],
                    'pluginOptions' => ['allowClear' => true],
                    'pluginEvents' => ['change' => 'function(){ submitForm()}']
                ])
                ?>
            </div>
        </div>
    </div>

    <!-- 关键字 -->
    <?= $form->field($model, 'keyword')->textInput([
        'maxlength' => true, 
        'placeholder' => I18NUitl::t('app', '{Please}{Input}{Goods}{Name}{Or}{Tag}'),
        'onchange' => 'submitForm()',
        ]) ?>

    <?php //$form->field($model, 'id') ?>

    <?php //$form->field($model, 'category_id') ?>

    <?php //$form->field($model, 'model_id') ?>

    <?php //$form->field($model, 'owner_id') ?>

    <?php // echo $form->field($model, 'goods_name') ?>

    <?php // echo $form->field($model, 'goods_cost') ?>

    <?php // echo $form->field($model, 'goods_price') ?>

    <?php // echo $form->field($model, 'goods_des') ?>

    <?php // echo $form->field($model, 'cover_url') ?>

    <?php // echo $form->field($model, 'video_url') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'tags') ?>

    <?php // echo $form->field($model, 'store_count') ?>

    <?php // echo $form->field($model, 'comment_count') ?>

    <?php // echo $form->field($model, 'click_count') ?>

    <?php // echo $form->field($model, 'share_count') ?>

    <?php // echo $form->field($model, 'like_count') ?>

    <?php // echo $form->field($model, 'sale_count') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at')  ?>

<?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
    
    // 提交表单    
    function submitForm (){
        $('#goods-search-form').submit();
    }   
    
</script>