<?php

use common\models\AdminUser;
use common\models\goods\Goods;
use common\models\goods\GoodsCategory;
use common\models\goods\searchs\GoodsSearch;
use common\models\order\Order;
use common\utils\I18NUitl;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model GoodsSearch */
/* @var $form ActiveForm */

/* 其它选项下拉模板 */
?>

<div class="goods-search pull-right">

    <?php
    $form = ActiveForm::begin([
        'id' => 'search-form',
        'action' => ['index'],
        'method' => 'get',
    ]);
    ?>

    <!-- 商品编号 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown">
            <?=
            Html::activeTextInput($model, 'goods_sn', [
                'class' => 'form-control',
                'placeholder' => I18NUitl::t('app', '{Please}{Input}{Goods}{SN}'),
                'onBlur' => 'submitForm()',
            ])
            ?>
        </div>
    </div>

    <!-- 类目 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown" style="width:120px">
            <?=
            Select2::widget([
                'model' => $model,
                'attribute' => 'category_id',
                'data' => GoodsCategory::getCategory(),
                'options' => ['placeholder' => Yii::t('app', 'All')],
                'pluginOptions' => ['allowClear' => true],
                'pluginEvents' => ['change' => 'function(){ submitForm()}']
            ])
            ?>
        </div>
    </div>

    <!-- 拥有人 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown">
            <?=
            Select2::widget([
                'model' => $model,
                'attribute' => 'owner_id',
                'data' => AdminUser::getUserByType(AdminUser::TYPE_OWNER),
                'options' => ['placeholder' => Yii::t('app', 'Owner')],
                'pluginOptions' => ['allowClear' => true],
                'pluginEvents' => ['change' => 'function(){ submitForm()}']
            ]);
            ?>
        </div>
    </div>

    <!-- 创建人 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown">
            <?=
            Select2::widget([
                'model' => $model,
                'attribute' => 'created_by',
                'data' => AdminUser::getUserByType(AdminUser::TYPE_GENERAL),
                'options' => ['placeholder' => Yii::t('app', 'Created By')],
                'pluginOptions' => ['allowClear' => true],
                'pluginEvents' => ['change' => 'function(){ submitForm()}']
            ]);
            ?>
        </div>
    </div>

    <!-- 状态 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown">
            <?=
            Select2::widget([
                'model' => $model,
                'attribute' => 'status',
                'data' => Goods::$statusKeyMap,
                'options' => ['placeholder' => Yii::t('app', 'Status')],
                'pluginOptions' => ['allowClear' => true],
                'pluginEvents' => ['change' => 'function(){ submitForm()}']
            ]);
            ?>
        </div>
    </div>

    <!-- 关键字 -->
    <div class="dep-dropdown-box">
        <div class="dep-dropdown">
            <?=
            Html::activeTextInput($model, 'keyword', [
                'class' => 'form-control',
                'placeholder' => I18NUitl::t('app', '{Please}{Input}{Goods}{Name}{Or}{Tag}'),
                'onBlur' => 'submitForm()',
            ])
            ?>
        </div>
    </div>

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

<?php // echo $form->field($model, 'updated_at')   ?>

<?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">

    // 提交表单    
    function submitForm() {
        $('#search-form').submit();
    }

</script>