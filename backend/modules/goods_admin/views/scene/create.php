<?php

use common\models\goods\GoodsScene;
use common\utils\I18NUitl;
use yii\web\View;

/* @var $this View */
/* @var $model GoodsScene */

$this->title = I18NUitl::t('app', '{Create}{Scene}');
$this->params['breadcrumbs'][] = ['label' => $goodsModel->goods_name, 'url' => ['/goods_admin/goods/view', 'id' => $model->goods_id]];
$this->params['breadcrumbs'][] = ['label' => I18NUitl::t('app', '{Scene}{List}'), 'url' => ['index' , 'goods_id' => $model->goods_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-scene-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
