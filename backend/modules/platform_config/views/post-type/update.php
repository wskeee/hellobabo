<?php

use common\models\platform\PostType;
use common\utils\I18NUitl;
use yii\web\View;

/* @var $this View */
/* @var $model PostType */

$this->title = I18NUitl::t('app', '{Update}{Post}{Type}: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => I18NUitl::t('app', '{Post}{Type}{List}'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="post-type-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
