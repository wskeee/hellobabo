<?php

use common\utils\I18NUitl;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\platform\Agency */

$this->title = I18NUitl::t('app', '{Create}{Agency}');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Agencies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agency-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
