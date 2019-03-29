<?php

use common\models\goods\SceneGroup;
use common\utils\I18NUitl;
use yii\web\View;

/* @var $this View */
/* @var $model SceneGroup */

$this->title = I18NUitl::t('app', '{Update}{Scene}{Group}: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => I18NUitl::t('app', '{Scene}{Groups}'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="scene-group-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
