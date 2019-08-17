<?php

use common\models\goods\GoodsScenePage;
use common\utils\I18NUitl;
use yii\web\View;

/* @var $this View */
/* @var $model GoodsScenePage */

$this->title = I18NUitl::t('app', '{Create} {Goods Scene Page}');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Goods Scene Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-scene-page-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
