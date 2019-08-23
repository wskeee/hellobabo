<?php

use common\models\order\Order;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $model Order */
?>
<div class="wsk-panel goods-info">
    <div class="title">操作信息</div>
    <div class="body">
        <?=
        GridView::widget([
            'dataProvider' => new ArrayDataProvider(['allModels' => $model->actionLogs,]),
            'tableOptions' => ['class' => 'table table-striped table-bordered wsk-table'],
            'layout' => "{items}",
            'columns' => [
                [
                    'attribute' => 'title',
                    'headerOptions' => ['style' => 'width:120px;'],
                ],
                'content',
                [
                    'attribute' => 'created_by',
                    'headerOptions' => ['style' => 'width:120px;'],
                    'value' => function($model) {
                        return $model->created_by == 0 ? '用户' : $model->creater->nickname;
                    }
                ],
                [
                    'attribute' => 'created_at',
                    'headerOptions' => ['style' => 'width:80px;'],
                    'value' => function($model) {
                        return date('Y-m-d H:s:i', $model->created_at);
                    }
                ],
            ],
        ]);
        ?>
    </div>
</div>