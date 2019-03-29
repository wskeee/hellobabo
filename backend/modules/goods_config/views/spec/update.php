<?php

use common\models\goods\GoodsSpec;
use common\utils\I18NUitl;
use yii\web\View;

/* @var $this View */
/* @var $model GoodsSpec */

$this->title = I18NUitl::t('app', '{Update}{Spec}: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => I18NUitl::t('app', '{Model}:{Name}', ['Name' => $model->goodsModel->name]), 'url' => ['/goods_config/goods-model/view', 'id' => $model->model_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="spec-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
