<?php

use common\models\goods\ShootingFace;
use common\utils\I18NUitl;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model ShootingFace */

$this->title = I18NUitl::t('app', '{Update} {Shooting Face}: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shooting Faces'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="shooting-face-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
