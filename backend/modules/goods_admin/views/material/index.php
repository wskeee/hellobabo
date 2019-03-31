<?php

use common\models\goods\Goods;
use common\models\goods\GoodsMaterial;
use common\models\goods\searchs\GoodsMaterialSearch;
use common\utils\I18NUitl;
use common\widgets\grid\GridViewChangeSelfColumn;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel GoodsMaterialSearch */
/* @var $dataProvider ActiveDataProvider */
/* @var $goodsModel Goods */

$this->title = I18NUitl::t('app', '{Goods}{Material}{List}');
$this->params['breadcrumbs'][] = ['label' => $goodsModel->goods_name, 'url' => ['/goods_admin/goods/view', 'id' => $goodsModel->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-material-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="wsk-panel">
        <div class="title">
            <?= I18NUitl::t('app', '{Material}{Admin}') ?>
            <?= Html::a(I18NUitl::t('app', '{Create}{Material}'), ['create', 'goods_id' => $goodsModel->id], ['class' => 'btn btn-success pull-right']) ?>
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
                    [
                        'attribute' => 'input_type',
                        'value' => function($model) {
                            return GoodsMaterial::$inputTypeMap[$model->input_type];
                        },
                    ],
                    [
                        'attribute' => 'is_required',
                        'class' => GridViewChangeSelfColumn::class,
                        'plugOptions' => [
                            'url' => '/goods_admin/material/change-value'
                        ],
                    ],
                    [
                        'attribute' => 'sort_order',
                        'class' => GridViewChangeSelfColumn::class,
                        'headerOptions' => ['style' => 'width:100px'],
                        'plugOptions' => [
                            'type' => 'input',
                            'url' => '/goods_admin/material/change-value'
                        ],
                    ],
                    'des',
                    [
                        'attribute' => 'is_del',
                        'class' => GridViewChangeSelfColumn::class,
                        'headerOptions' => ['style' => 'width:100px'],
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'buttons' => [
                            'updata' => function ($url, $model) {
                                return Html::a(Yii::t('yii', 'Update'), ['/goods_admin/material/update', 'id' => $model->id], ['class' => 'btn btn-default']);
                            },
                            'view' => function($url, $model) {
                                return Html::a(I18NUitl::t('app', '{Config}{Value}'), ['/goods_admin/material/view', 'id' => $model->id], ['class' => 'btn btn-primary']);
                            },
                            'delete' => function ($url, $model) {
                                return Html::a(Yii::t('yii', 'Delete'), ['/goods_admin/material/delete', 'id' => $model->id], ['class' => 'btn btn-danger',
                                            'data' => [
                                                'pjax' => 0,
                                                'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                                'method' => 'post',
                                            ],
                                ]);
                            },
                        ],
                        'headerOptions' => ['style' => 'width:200px'],
                        'template' => '{updata} {view} {delete}',
                    ],
                ],
            ]);
            ?>
        </div>
    </div>


</div>
