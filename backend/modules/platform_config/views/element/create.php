<?php

use common\models\goods\GoodsElement;
use common\utils\I18NUitl;
use yii\web\View;

/* @var $this View */
/* @var $model GoodsElement */

$this->title = I18NUitl::t('app', '{Create}{Element}');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Elements'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-element-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
