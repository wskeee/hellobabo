<?php

use common\models\goods\GoodsModel;
use common\utils\I18NUitl;
use yii\web\View;

/* @var $this View */
/* @var $model GoodsModel */

$this->title = I18NUitl::t('app', '{Create} {Goods}{Model}');
$this->params['breadcrumbs'][] = ['label' => I18NUitl::t('app', 'Models'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-model-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
