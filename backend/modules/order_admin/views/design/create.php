<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\order\WorkflowDesign */

$this->title = Yii::t('app', 'Create Workflow Design');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Workflow Designs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="workflow-design-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
