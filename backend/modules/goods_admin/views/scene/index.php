<?php

use common\models\goods\Goods;
use common\models\goods\GoodsScene;
use common\models\goods\searchs\GoodsSceneSearch;
use common\utils\I18NUitl;
use common\widgets\grid\GridViewChangeSelfColumn;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel GoodsSceneSearch */
/* @var $dataProvider ActiveDataProvider */
/* @var $goodsModel Goods */

$this->title = I18NUitl::t('app', '{Scene}{List}');
$this->params['breadcrumbs'][] = ['label' => $goodsModel->goods_name, 'url' => ['/goods_admin/goods/view', 'id' => $goodsModel->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-scene-index">

    <div class="wsk-panel">
        <div class="title">
            <?= Html::a(I18NUitl::t('app', '{Create}{Scene}'), ['create', 'goods_id' => $goodsModel->id], ['class' => 'btn btn-success']) ?>
            <?= $this->render('_search', ['model' => $searchModel, 'goodsModel' => $goodsModel]) ?>
        </div>
        <div class="body">
            <?=
            GridView::widget([
                'tableOptions' => ['class' => 'table table-striped table-bordered wsk-table'],
                'layout' => "{items}\n{summary}\n{pager}",
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn', 'headerOptions' => ['style' => 'width:60px']],
                    'name',
                    "group.name:text:{$searchModel->getAttributeLabel('group_id')}",
                    [
                        'attribute' => 'effect_url',
                        'format' => 'raw',
                        'headerOptions' => ['style' => 'width:100px'],
                        'value' => function($model) {
                            return Html::img($model->effect_url, ['style' => 'height:64px']);
                        }
                    ],
                    /*
                      [
                      'attribute' => 'demo_url',
                      'format' => 'raw',
                      'headerOptions' => ['style' => 'width:100px'],
                      'value' => function($model) {
                      return Html::img($model->demo_url, ['style' => 'height:64px']);
                      }
                      ], */
                    [
                        'attribute' => 'source_url',
                        'format' => 'raw',
                        'headerOptions' => ['style' => 'width:74px'],
                        'value' => function($model) {
                            return Html::img($model->source_url, ['style' => 'height:64px']);
                        }
                    ],
                    'des:text',
                    [
                        'attribute' => 'is_selected',
                        'class' => GridViewChangeSelfColumn::class,
                        'headerOptions' => ['style' => 'width:80px'],
                        'plugOptions' => [
                            'url' => '/goods_admin/scene/change-value'
                        ],
                    ],
                    /*
                      [
                      'attribute' => 'is_required',
                      'class' => GridViewChangeSelfColumn::class,
                      'headerOptions' => ['style' => 'width:80px'],
                      'plugOptions' => [
                      'url' => '/goods_admin/scene/change-value'
                      ],
                      ], */
                    [
                        'attribute' => 'immutable',
                        'class' => GridViewChangeSelfColumn::class,
                        'headerOptions' => ['style' => 'width:80px'],
                        'plugOptions' => [
                            'url' => '/goods_admin/scene/change-value'
                        ],
                    ],
                    [
                        'attribute' => 'pos',
                        'value' => function($model) {
                            return GoodsScene::$posNameMap[$model->pos];
                        }
                    ],
                    [
                        'attribute' => 'sort_order',
                        'class' => GridViewChangeSelfColumn::class,
                        'headerOptions' => ['style' => 'width:80px'],
                        'plugOptions' => [
                            'type' => 'input',
                            'url' => '/goods_admin/scene/change-value'
                        ],
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'buttons' => [
                            'setting' => function ($url, $model) {
                                return Html::a(Yii::t('app', 'Setting'), ['/goods_admin/scene-page/index', 'scene_id' => $model->id], ['class' => 'btn btn-primary']);
                            },
                            'updata' => function ($url, $model) {
                                return Html::a(Yii::t('yii', 'Update'), ['/goods_admin/scene/update', 'id' => $model->id], ['class' => 'btn btn-default']);
                            },
                            'delete' => function ($url, $model) {
                                return Html::a(Yii::t('yii', 'Delete'), ['/goods_admin/scene/delete', 'id' => $model->id], ['class' => 'btn btn-danger',
                                            'data' => [
                                                'pjax' => 0,
                                                'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                                'method' => 'post',
                                            ],
                                ]);
                            },
                        ],
                        'headerOptions' => ['style' => 'width:200px'],
                        'template' => '{setting} {updata} {delete}',
                    ],
                ],
            ]);
            ?>
        </div>
    </div>
</div>
