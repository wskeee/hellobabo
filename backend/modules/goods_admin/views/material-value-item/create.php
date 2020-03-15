<?php

use common\utils\I18NUitl;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\goods\GoodsMaterialValueItem */


$this->title = I18NUitl::t('app', '{Create} {Goods Material Value Item}');
$this->params['breadcrumbs'][] = ['label' => I18NUitl::t('app', "{Goods Material Value Items}：{$model->materialValue->name}"), 'url' => ['index', 'material_value_id' => $model->material_value_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-material-value-item-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
