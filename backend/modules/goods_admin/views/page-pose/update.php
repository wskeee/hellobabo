<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\goods\GoodsPagePose */

$this->title = Yii::t('app', 'Update Goods Page Pose: {name}', [
    'name' => $model->page_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Goods Page Poses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->page_id, 'url' => ['view', 'id' => $model->page_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="goods-page-pose-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
