<?php

use common\models\goods\GoodsScene;
use common\models\goods\GoodsScenePage;
use common\utils\I18NUitl;
use yii\web\View;

/* @var $this View */
/* @var $model GoodsScene */
/* @var $scene_material_model GoodsSceneMaterial */
/* @var $page_model GoodsScenePage */

$this->title = I18NUitl::t('app', '{Update}{Scene}: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => $model->goods->goods_name, 'url' => ['/goods_admin/goods/view', 'id' => $model->goods_id]];
$this->params['breadcrumbs'][] = ['label' => I18NUitl::t('app', '{Scene}{List}'), 'url' => ['index' , 'goods_id' => $model->goods_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="goods-scene-update">

    <?= $this->render('_form_group', [
        'model' => $model,
        'scene_material_model' => $scene_material_model,
        'page_model' => $page_model,
    ]) ?>

</div>
