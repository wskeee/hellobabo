<?php

use common\models\goods\Goods;
use common\utils\I18NUitl;
use yii\web\View;

/* @var $this View */
/* @var $model Goods */

$this->title = I18NUitl::t('app', '{Update}{Goods}: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => I18NUitl::t('app', '{Goods}{List}'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="goods-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
