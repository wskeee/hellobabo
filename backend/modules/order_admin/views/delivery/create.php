<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\order\WorkflowDelivery */

$this->title = Yii::t('app', 'Create Workflow Delivery');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Workflow Deliveries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="workflow-delivery-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
