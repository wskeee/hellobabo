<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\order\WorkflowPrint */

$this->title = Yii::t('app', 'Create Workflow Print');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Workflow Prints'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="workflow-print-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>