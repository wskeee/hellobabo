<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\platform\AgencyServicer */

$this->title = Yii::t('app', 'Create Agency Servicer');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Agency Servicers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agency-servicer-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
