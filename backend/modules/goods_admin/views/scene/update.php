<?php

use common\models\goods\GoodsScene;
use common\utils\I18NUitl;
use yii\web\View;

/* @var $this View */
/* @var $model GoodsScene */

$this->title = I18NUitl::t('app', '{Update}{Scene}: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => $model->goods->goods_name, 'url' => ['/goods_admin/goods/view', 'id' => $model->goods_id]];
$this->params['breadcrumbs'][] = ['label' => I18NUitl::t('app', '{Scene}{List}'), 'url' => ['index' , 'goods_id' => $model->goods_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="goods-scene-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
