<?php

use common\models\goods\SceneGroup;
use common\utils\I18NUitl;
use yii\web\View;

/* @var $this View */
/* @var $model SceneGroup */

$this->title = I18NUitl::t('app', '{Create}{Scene}{Group}');
$this->params['breadcrumbs'][] = ['label' => I18NUitl::t('app', '{Scene} {Groups}'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scene-group-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
