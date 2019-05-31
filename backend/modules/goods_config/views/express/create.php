<?php

use common\models\platform\Express;
use common\utils\I18NUitl;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model Express */

$this->title = I18NUitl::t('app', '{Create}{Express}');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Expresses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="express-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
