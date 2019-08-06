<?php

use common\models\goods\ShootingAngle;
use common\utils\I18NUitl;
use yii\web\View;

/* @var $this View */
/* @var $model ShootingAngle */

$this->title = I18NUitl::t('app', '{Update} {Shooting Angle}: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shooting Angles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="shooting-angle-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
