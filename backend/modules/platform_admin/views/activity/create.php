<?php

use common\utils\I18NUitl;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\activity\Activity */

$this->title = I18NUitl::t('app', '{Create}{Activity}');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Activities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
