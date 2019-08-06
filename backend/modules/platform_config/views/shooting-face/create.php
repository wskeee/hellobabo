<?php

use common\models\goods\ShootingFace;
use common\utils\I18NUitl;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model ShootingFace */

$this->title = I18NUitl::t('app', '{Create} {Shooting Face}');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shooting Faces'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shooting-face-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
