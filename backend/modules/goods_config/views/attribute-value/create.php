<?php

use common\models\goods\GoodsAttributeValue;
use common\utils\I18NUitl;
use yii\web\View;

/* @var $this View */
/* @var $model GoodsAttributeValue */

$this->title = I18NUitl::t('app', '{Create}{Goods}{Attribute}{Value}');
$this->params['breadcrumbs'][] = ['label' => I18NUitl::t('app', '{Goods}{Attribute}{Value}'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-attribute-value-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
