<?php

use common\models\goods\GoodsModel;
use yii\data\ArrayDataProvider;
use yii\web\View;
use yii\web\YiiAsset;

/* @var $this View */
/* @var $model GoodsModel */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Models'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="goods-model-view">
    <?=
    $this->render('../attribute/index', [
        'goodsModel' => $model,
        'dataProvider' => new ArrayDataProvider([
            'allModels' => $attrs,
            'key' => 'id',
        ])])
    ?>
    <?=
    $this->render('../spec/index', [
        'goodsModel' => $model,
        'dataProvider' => new ArrayDataProvider([
            'allModels' => $specs,
            'key' => 'id',
        ])])
    ?>
</div>
