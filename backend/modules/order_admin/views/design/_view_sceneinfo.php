<?php

use common\models\order\Order;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $model Order */
?>
<div class="wsk-panel goods-info">
    <div class="title">场景信息</div>
    <div class="body">
        <?=
        GridView::widget([
            'dataProvider' => new ArrayDataProvider(['allModels' => $model->orderGoodsScenes,]),
           // 'filterModel' => new OrderGoodsMaterial(),
            'tableOptions' => ['class' => 'table table-striped table-bordered wsk-table'],
            'layout' => "{items}",
            'columns' => [
                [
                    'attribute' => 'name',
                    'headerOptions' => ['style' => 'width:120px;'],
                ],
                [
                    'attribute' => 'effect_url',
                    'headerOptions' => ['style' => 'width:100px;'],
                    'format' => 'raw',
                    'value' => function($model) {
                        return Html::img($model->effect_url, ['style' => 'width:48px;height:48px;']);
                    }
                ],
                [
                    'attribute' => 'demo_url',
                    'headerOptions' => ['style' => 'width:100px;'],
                    'format' => 'raw',
                    'value' => function($model) {
                        return Html::img($model->demo_url, ['style' => 'width:48px;height:48px;']);
                    }
                ],
                [
                    'attribute' => 'user_img_url',
                    'headerOptions' => ['style' => 'width:100px;'],
                    'format' => 'raw',
                    'value' => function($model) {
                        return Html::img($model->user_img_url, ['style' => 'width:48px;height:48px;']);
                    }
                ],
                [
                    'attribute' => 'finish_url',
                    'headerOptions' => ['style' => 'width:100px;'],
                    'format' => 'raw',
                    'value' => function($model) {
                        return Html::img($model->finish_url, ['style' => 'width:48px;height:48px;']);
                    }
                ],
                'des'
            ],
        ]);
        ?>
    </div>
</div>