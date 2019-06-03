<?php

use common\models\goods\GoodsModel;
use common\utils\I18NUitl;
use yii\web\View;

/* @var $this View */
/* @var $model GoodsModel */

$this->title = I18NUitl::t('app', '{Update} {Goods}{Model}: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Models'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="goods-model-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
