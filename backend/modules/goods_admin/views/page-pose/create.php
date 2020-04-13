<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\goods\GoodsPagePose */

$this->title = Yii::t('app', 'Create Goods Page Pose');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Goods Page Poses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-page-pose-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
