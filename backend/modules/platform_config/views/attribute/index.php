<?php

use common\models\goods\GoodsAttribute;
use common\models\goods\GoodsModel;
use common\models\goods\searchs\GoodsAttributeSearch;
use common\utils\I18NUitl;
use common\widgets\grid\GridViewChangeSelfColumn;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel GoodsAttributeSearch */
/* @var $dataProvider ActiveDataProvider */
/* @var $goodsModel GoodsModel */
?>
<div class="goods-attribute-index">

    <div class="wsk-panel">
        <div class="title">
            <?= Yii::t('app', 'Attribute') ?>
            <?= Html::a(I18NUitl::t('app', '{Add}{Attribute}'), ['/platform_config/attribute/create', 'model_id' => $goodsModel->id], ['class' => ['btn btn-primary pull-right'],]) ?>
        </div>
        <div class="body">
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-striped table-bordered wsk-table'],
                'layout' => "{items}\n{summary}\n{pager}",
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn', 'headerOptions' => ['style' => 'width:60px']],
                    'name',
                    [
                        'attribute' => 'input_type',
                        'value' => function($model) {
                            return GoodsAttribute::$inputTypeMap[$model->input_type];
                        },
                    ],
                    'value_length',
                    [
                        'attribute' => 'is_required',
                        'class' => GridViewChangeSelfColumn::class,
                        'plugOptions' => [
                            'url' => '/platform_config/attribute/change-value'
                        ],
                    ],
                    [
                        'attribute' => 'index_type',
                        'class' => GridViewChangeSelfColumn::class,
                        'plugOptions' => [
                            'url' => '/platform_config/attribute/change-value'
                        ],
                    ],
                    [
                        'attribute' => 'sort_order',
                        'class' => GridViewChangeSelfColumn::class,
                        'headerOptions' => ['style' => 'width:100px'],
                        'plugOptions' => [
                            'type' => 'input',
                            'url' => '/platform_config/attribute/change-value'
                        ],
                    ],
                    [
                        'attribute' => 'is_del',
                        'class' => GridViewChangeSelfColumn::class,
                        'headerOptions' => ['style' => 'width:100px'],
                        'plugOptions' => [
                            'labels' => ['禁用', '启用'],
                            'values' => [1, 0],
                            'url' => '/platform_config/attribute/change-value'
                        ],
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'buttons' => [
                            'updata' => function ($url, $model) {
                                return Html::a(Yii::t('yii', 'Update'), ['/platform_config/attribute/update', 'id' => $model->id], ['class' => 'btn btn-default']);
                            },
                            'view' => function($url, $model) {
                                return Html::a(I18NUitl::t('app', '{Config}{Value}'), ['/platform_config/attribute/view', 'id' => $model->id], ['class' => 'btn btn-primary']);
                            },
                            'delete' => function ($url, $model) {
                                return Html::a(Yii::t('yii', 'Delete'), ['/platform_config/attribute/delete', 'id' => $model->id], ['class' => 'btn btn-danger',
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

<?php
$js = <<<JS
    
  
JS;
$this->registerJs($js, View::POS_READY);
?>