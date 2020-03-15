<?php

use common\utils\I18NUitl;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\goods\GoodsMaterialValueItem */

$this->title = I18NUitl::t('app', '{Update} {Goods Material Value Item}: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Goods Material Value Items'), 'url' => ['index','material_value_id' => $model->material_value_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="goods-material-value-item-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
