<?php

use common\models\goods\Goods;
use common\utils\I18NUitl;
use yii\web\View;

/* @var $this View */
/* @var $model Goods */

$this->title = I18NUitl::t('app', '{Create}{Goods}');
$this->params['breadcrumbs'][] = ['label' => I18NUitl::t('app', '{Goods}{List}'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
