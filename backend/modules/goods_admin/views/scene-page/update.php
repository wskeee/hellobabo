<?php

use common\models\goods\GoodsScenePage;
use common\utils\I18NUitl;
use yii\web\View;

/* @var $this View */
/* @var $model GoodsScenePage */

$this->title = I18NUitl::t('app', '{Update} {Goods Scene Page}: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Goods Scene Pages'), 'url' => ['index','scene_id' => $model->scene_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="goods-scene-page-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
