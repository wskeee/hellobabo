<?php

use common\models\order\Order;
use common\models\order\OrderGoods;
use common\modules\rbac\components\ResourceHelper;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;

/* @var $model Order */
$dataProvider = $model->orderGoods;
?>
<div class="wsk-panel">
    <div class="title">绘本信息</div>
    <div class="body order-goodsinfo">
        <?=
        GridView::widget([
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $dataProvider,
                    ]),
            'tableOptions' => ['class' => 'table table-striped table-bordered wsk-table'],
            'layout' => "{items}\n{summary}\n{pager}",
            //'filterModel' => $searchModel,
            'columns' => [
                [
                    'attribute' => 'goods_name',
                    'headerOptions' => ['style' => 'width:120px;'],
                ],
                [
                    'attribute' => 'spec_key_name',
                    'headerOptions' => ['style' => 'width:120px;'],
                ],
                [
                    'attribute' => 'status',
                    'headerOptions' => ['style' => 'width:120px;'],
                    'value' => function($model) {
                        return OrderGoods::$statusNameMap[$model->status];
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return ResourceHelper::a(Yii::t('app', 'View'), ['/order_admin/order-goods/view', 'id' => $model->id], ['class' => 'btn btn-default']);
                        },
                    ],
                    'headerOptions' => ['style' => 'width:100px'],
                    'template' => '{view}',
                ],
            ],
        ]);
        ?>
    </div>
</div>