<?php

use common\models\order\OrderGoods;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $model OrderGoods */
?>
<div class="wsk-panel goods-info">
    <div class="title">素材信息</div>
    <div class="body">
        <?=
        GridView::widget([
            'dataProvider' => new ArrayDataProvider(['allModels' => $model->orderGoodsMaterials,]),
           // 'filterModel' => new OrderGoodsMaterial(),
            'tableOptions' => ['class' => 'table table-striped table-bordered wsk-table'],
            'layout' => "{items}",
            'columns' => [
                [
                    'attribute' => 'value_name',
                    'headerOptions' => ['style' => 'width:120px;'],
                ],
                [
                    'attribute' => 'value_source_url',
                    'headerOptions' => ['style' => 'width:50px;'],
                    'format' => 'raw',
                    'value' => function($model) {
                        return Html::img($model->value_source_url, ['style' => 'width:32px;height:32px;']);
                    }
                ],
                'value_des'
            ],
        ]);
        ?>
    </div>
</div>