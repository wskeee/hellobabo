<?php

use common\models\goods\GoodsMaterial;
use common\utils\I18NUitl;
use yii\helpers\Html;
use yii\web\View;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model GoodsMaterial */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => $model->goods->goods_name, 'url' => ['/goods_admin/goods/view', 'id' => $model->goods_id]];
$this->params['breadcrumbs'][] = ['label' => I18NUitl::t('app', '{Material}{List}'), 'url' => ['index' , 'goods_id' => $model->goods_id]];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="goods-material-view">

    <!--属性值-->
    <div class="wsk-panel pull-left">

        <div class="title">
            <div class="pull-left">
                <?= I18NUitl::t('app', '{Attribute}{Value}') ?>
            </div>

            <div class="btngroup pull-right">
                <span class="loading" style="display: none;"></span>
                <?= Html::a(Yii::t('app', 'Add'), ['material-value/create', 'attribute_id' => $model->id], ['id' => 'btn-addValue', 'class' => 'btn btn-primary btn-flat']);?>
            </div>
        </div>

        <?=
        $this->render('/material-value/index', [
            'dataProvider' => $dataProvider,
        ])
        ?>

    </div>    

</div>
