<?php

use common\models\goods\GoodsCategory;
use yii\web\View;

/* @var $this View */
/* @var $model GoodsCategory */

$this->title = Yii::t('app', '{Create}{Goods}{Category}', [
    'Create' => Yii::t('app', 'Create'), 'Goods' => Yii::t('app', 'Goods'), 'Category' => Yii::t('app', 'Category')
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '{Goods}{Category}', [
    'Goods' => Yii::t('app', 'Goods'), 'Category' => Yii::t('app', 'Category')
]), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-category-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
