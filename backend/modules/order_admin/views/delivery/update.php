<?php

use common\models\order\WorkflowDelivery;
use common\utils\I18NUitl;
use yii\web\View;

/* @var $this View */
/* @var $model WorkflowDelivery */

$this->title = I18NUitl::t('app', '{Update} {Delivery}: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Deliveries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="workflow-delivery-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
