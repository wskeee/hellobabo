<?php

use common\models\goods\Goods;
use common\models\goods\GoodsMaterial;
use common\utils\I18NUitl;
use yii\web\View;

/* @var $this View */
/* @var $model GoodsMaterial */
/* @var $goodsModel Goods */

$this->title = I18NUitl::t('app', '{Create}{Material}');
$this->params['breadcrumbs'][] = ['label' => $goodsModel->goods_name, 'url' => ['/goods_admin/goods/view', 'id' => $model->goods_id]];
$this->params['breadcrumbs'][] = ['label' => I18NUitl::t('app', '{Material}{List}'), 'url' => ['index' , 'goods_id' => $model->goods_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-material-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
