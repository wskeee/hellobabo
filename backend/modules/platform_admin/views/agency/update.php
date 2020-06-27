<?php

use common\utils\I18NUitl;

/* @var $this yii\web\View */
/* @var $model common\models\platform\Agency */

$this->title = I18NUitl::t('app', '{Update}: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Agencies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="agency-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
