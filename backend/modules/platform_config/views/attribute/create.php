<?php

use common\models\goods\GoodsAttribute;
use common\utils\I18NUitl;
use yii\web\View;

/* @var $this View */
/* @var $model GoodsAttribute */

$this->title = I18NUitl::t('app', '{Create}{Goods}{Attribute}');
$this->params['breadcrumbs'][] = ['label' => I18NUitl::t('app', '{Model}:{Name}', ['Name' => $model->goodsModel->name]), 'url' => ['/platform_config/goods-model/view', 'id' => $model->model_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-attribute-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
