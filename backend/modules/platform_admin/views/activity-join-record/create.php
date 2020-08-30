<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\activity\ActivityJoinRecord */

$this->title = Yii::t('app', 'Create Activity Join Record');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Activity Join Records'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-join-record-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
