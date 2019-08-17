<?php

use common\models\goods\ShootingAngle;
use common\utils\I18NUitl;
use yii\web\View;

/* @var $this View */
/* @var $model ShootingAngle */

$this->title = I18NUitl::t('app', '{Create} {Shooting Angle}');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shooting Angles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shooting-angle-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
