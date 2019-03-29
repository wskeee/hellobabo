<?php

use common\models\goods\GoodsCategory;
use yii\web\View;

/* @var $this View */
/* @var $model GoodsCategory */

$this->title = Yii::t('app', "{Update}{Goods}{Category}: {$model->name}", [
    'Update' => Yii::t('app', 'Update'), 'Goods' => Yii::t('app', 'Goods'), 'Category' => Yii::t('app', 'Category')
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '{Goods}{Category}', [
    'Goods' => Yii::t('app', 'Goods'), 'Category' => Yii::t('app', 'Category')
]), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="goods-category-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
