<?php

use common\models\goods\GoodsMaterial;
use common\utils\I18NUitl;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model GoodsMaterial */

$this->title = I18NUitl::t('app', '{Update}{Material}: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => $model->goods->goods_name, 'url' => ['/goods_admin/goods/view', 'id' => $model->goods_id]];
$this->params['breadcrumbs'][] = ['label' => I18NUitl::t('app', '{Material}{List}'), 'url' => ['index' , 'goods_id' => $model->goods_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="goods-material-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
