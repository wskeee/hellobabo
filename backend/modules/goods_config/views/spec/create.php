<?php

use common\models\goods\GoodsSpec;
use common\utils\I18NUitl;
use yii\web\View;

/* @var $this View */
/* @var $model GoodsSpec */

$this->title = I18NUitl::t('app', '{Create}{Goods}{Spec}');
$this->params['breadcrumbs'][] = ['label' => I18NUitl::t('app', '{Model}:{Name}', ['Name' => $model->goodsModel->name]), 'url' => ['/goods_config/goods-model/view', 'id' => $model->model_id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="spec-create">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
