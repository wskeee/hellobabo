<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\activity\VoteActivityHand */

$this->title = Yii::t('app', 'Create Vote Activity Hand');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vote Activity Hands'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vote-activity-hand-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
