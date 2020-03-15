<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\goods\GoodsScenePreview */

$this->title = Yii::t('app', 'Update Goods Scene Preview: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Goods Scene Previews'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="goods-scene-preview-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
