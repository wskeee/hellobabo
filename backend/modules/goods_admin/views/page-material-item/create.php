<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\goods\GoodsScenePageMaterialItem */

$this->title = Yii::t('app', 'Create Goods Scene Page Material Item');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Goods Scene Page Material Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-scene-page-material-item-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
