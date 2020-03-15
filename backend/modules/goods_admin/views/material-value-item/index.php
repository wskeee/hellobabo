<?php

use common\models\goods\GoodsScenePage;
use common\utils\I18NUitl;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\goods\searchs\GoodsMaterialValueItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$materialValue = $searchModel->materialValue;
$material = $materialValue->material;
$goods = $material->goods;

$this->title = I18NUitl::t('app', "{Goods Material Value Items}：{$materialValue->name}");
$this->params['breadcrumbs'][] = ['label' => $goods->goods_name, 'url' => ['/goods_admin/goods/view', 'id' => $goods->id]];
$this->params['breadcrumbs'][] = ['label' => I18NUitl::t('app', '{Material}{List}'), 'url' => ['/goods_admin/material/index', 'goods_id' => $material->goods_id]];
$this->params['breadcrumbs'][] = ['label' => I18NUitl::t('app', "{Material}{Value}{List}"), 'url' => ['/goods_admin/material-value/index', 'material_id' => $materialValue->material_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-material-value-item-index">

    <p>
        <?= Html::a(I18NUitl::t('app', '{Create} {Goods Material Value Item}'), ['create', 'material_value_id' => $materialValue->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'tableOptions' => ['class' => 'table table-striped table-bordered wsk-table'],
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'source_id',
            'name',
            [
                'attribute' => 'effect_url',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::img($model->effect_url, ['style' => 'width:96px;']);
                }
            ],
            //'source_url:url',
            //'is_del',
            'des',
            [
                'label' => '已关联的场景页',
                'format' => 'raw',
                'value' => function ($model) {
                    $item_htmls = '';
                    foreach ($model->getScenePages() as $page) {
                        $image = Html::img($page['effect_url'], ['class' => 'item' ,'style' => 'height:120px;']);
                        $item_htmls .= Html::a($image,['/goods_admin/page-material-item/index','scene_page_id' => $page['id']],['target' => '_blank']);
                    }
                    return Html::tag('div', $item_htmls, ['class' => 'page-box']);
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'updata' => function ($url, $model) {
                        return Html::a(Yii::t('yii', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-default', 'data-toggle' => 'wsk-modal']);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a(Yii::t('yii', 'Delete'), ['delete', 'id' => $model->id], ['class' => 'btn btn-danger',
                            'data' => [
                                'pjax' => 0,
                                'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
                'headerOptions' => ['style' => 'width:150px'],
                'template' => '{updata} {delete}',
            ],
        ],
    ]); ?>
</div>
