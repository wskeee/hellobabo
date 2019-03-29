<?php

use common\models\goods\searchs\GoodsAttributeValueSearch;
use common\utils\I18NUitl;
use common\widgets\grid\GridViewChangeSelfColumn;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $searchModel GoodsAttributeValueSearch */
/* @var $dataProvider ActiveDataProvider */

?>
<div class="goods-attribute-value-index">

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-striped table-bordered wsk-table'],
        'layout' => "{items}\n{summary}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn',],
            [
                'attribute' => 'value',
                'class' => GridViewChangeSelfColumn::class,
                'plugOptions' => [
                    'type' => 'input',
                    'url' => Url::to(['/goods_config/attribute-value/change-value'], true),
                ],
            ],
            [
                'attribute' => 'is_del',
                'class' => GridViewChangeSelfColumn::class,
                'headerOptions' => ['style' => 'width:100px'],
                'plugOptions' => [
                    'labels' => ['禁用', '启用'],
                    'values' => [1, 0],
                    'url' => Url::to(['/goods_config/attribute-value/change-value'], true),
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        return Html::a(Yii::t('yii', 'Delete'), ['/goods_config/attribute-value/delete', 'id' => $model->id, 'attribute_id' => $model->attribute_id], [
                                    'class' => 'btn btn-danger',
                                    'data' => [
                                        'pjax' => 0,
                                        'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                        'method' => 'post',
                                    ],
                        ]);
                    },
                ],
                'template' => '{delete}',
                'headerOptions' => ['style' => 'width:200px'],
            ],
        ],
    ]);
    ?>

</div>
