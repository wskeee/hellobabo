<?php

use common\models\goods\GoodsAttributeValue;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model GoodsAttributeValue */

$this->title = Yii::t('app', "{Update}{Goods}{Attribute}{Value}", [
    'Update' => Yii::t('app', 'Update'), 'Goods' => Yii::t('app', 'Goods'), 
    'Attribute' => Yii::t('app', 'Attribute'), 'Value' => Yii::t('app', 'Value')
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '{Goods}{Attribute}{Value}', [
    'Goods' => Yii::t('app', 'Goods'), 'Attribute' => Yii::t('app', 'Attribute'), 'Value' => Yii::t('app', 'Value')
]), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="goods-attribute-value-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
