<?php

use common\models\activity\VoteActivity;
use common\utils\I18NUitl;
use yii\web\View;

/* @var $this View */
/* @var $model VoteActivity */

$this->title = I18NUitl::t('app', '{Update} {Vote Activity}: {name}', [
            'name' => $model->name,
        ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vote Activities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="vote-activity-update">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
