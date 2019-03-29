<?php

use common\models\goods\GoodsModel;
use common\models\goods\searchs\GoodsSpecSearch;
use common\utils\I18NUitl;
use common\widgets\grid\GridViewChangeSelfColumn;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel GoodsSpecSearch */
/* @var $dataProvider ActiveDataProvider */
/* @var $goodsModel GoodsModel */
?>
<div class="spec-index">

    <div class="wsk-panel">
        <div class="title">
            <?= Yii::t('app', 'Spec') ?>
            <?= Html::a(I18NUitl::t('app', '{Add}{Spec}'), ['/goods_config/spec/create', 'model_id' => $goodsModel->id], ['class' => ['btn btn-primary pull-right'],]) ?>
        </div>
        <div class="body">
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-striped table-bordered wsk-table'],
                'layout' => "{items}\n{summary}\n{pager}",
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn', 'headerOptions' => ['style' => 'width:60px']],
                    'name',
                    [
                        'attribute' => 'sort_order',
                        'class' => GridViewChangeSelfColumn::class,
                        'headerOptions' => ['style' => 'width:100px'],
                        'plugOptions' => [
                            'type' => 'input',
                            'url' => '/goods_config/spec/change-value'
                        ],
                    ],
                    [
                        'attribute' => 'is_del',
                        'class' => GridViewChangeSelfColumn::class,
                        'headerOptions' => ['style' => 'width:100px'],
                        'plugOptions' => [
                            'labels' => ['禁用', '启用'],
                            'values' => [1, 0],
                            'url' => '/goods_config/spec/change-value'
                        ],
                    ],
                    //'des',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'buttons' => [
                            'updata' => function ($url, $model) {
                                return Html::a(Yii::t('yii', 'Update'), ['/goods_config/spec/update', 'id' => $model->id], ['class' => 'btn btn-default']);
                            },
                            'delete' => function ($url, $model) {
                                return Html::a(Yii::t('yii', 'Delete'), ['/goods_config/spec/delete', 'id' => $model->id], ['class' => 'btn btn-danger',
                                            'data' => [
                                                'pjax' => 0,
                                                'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                                'method' => 'post',
                                            ],
                                ]);
                            },
                        ],
                        'headerOptions' => [
                            'style' => [
                                'width' => '200px',
                            ],
                        ],
                        'template' => '{updata} {delete}',
                    ],
                ],
            ]);
            ?>
        </div>
    </div>
</div>
