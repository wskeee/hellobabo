<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\platform\Issue */

$this->title = Yii::t('app', 'Create Issue');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Issues'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="issue-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
