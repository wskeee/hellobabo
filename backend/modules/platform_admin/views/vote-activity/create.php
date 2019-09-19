<?php

use common\models\activity\VoteActivity;
use common\utils\I18NUitl;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model VoteActivity */

$this->title = I18NUitl::t('app', '{Create} {Vote Activity}');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vote Activities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vote-activity-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
