<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\goods\GoodsScenePreview */

$this->title = Yii::t('app', 'Create Goods Scene Preview');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Goods Scene Previews'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-scene-preview-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
