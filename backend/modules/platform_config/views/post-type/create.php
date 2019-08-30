<?php

use common\models\platform\PostType;
use common\utils\I18NUitl;
use yii\web\View;

/* @var $this View */
/* @var $model PostType */

$this->title = I18NUitl::t('app', '{Create}{Post}{Type}');
$this->params['breadcrumbs'][] = ['label' => I18NUitl::t('app', '{Post}{Type}{List}'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-type-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
