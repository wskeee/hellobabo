<?php

use common\models\order\WorkflowDesign;
use yii\widgets\DetailView;
    
    /* @var $model WorkflowDesign */
?>
<div class="wsk-panel">
    <div class="title">基本信息</div>
    <div class="body order-baseinfo">
        <div>
            <?=
            DetailView::widget([
                'options' => ['class' => 'table table-striped table-bordered wsk-table-detail'],
                'model' => $model,
                'attributes' => [
                    'order_sn',
                    'order.goods_name',
                    'order.spec_key_name',
                    [
                        'attribute' => 'order.consignee',
                        'value' => function($model) {
                            return "{$model->order->consignee} ({$model->order->phone})";
                        }
                    ],
                    'order.user_note',
                ],
            ])
            ?>
        </div>
        <div>
            <?=
            DetailView::widget([
                'options' => ['class' => 'table table-striped table-bordered wsk-table-detail'],
                'model' => $model,
                'attributes' => [
                    [
                        'attribute' => 'status',
                        'value' => WorkflowDesign::$statusNameMap[$model->status]
                    ],
                    [
                        'attribute' => 'created_at',
                        'value' => $model->created_at == 0 ? null : date('Y-m-d H:i:s', $model->created_at)
                    ],
                    [
                        'attribute' => 'worker_id',
                        'value' => $model->worker_id == 0 ? null : $model->worker->nickname
                    ],
                    [
                        'attribute' => 'start_at',
                        'value' => $model->start_at == 0 ? null : date('Y-m-d H:i:s', $model->start_at)
                    ],
                    [
                        'attribute' => 'end_at',
                        'value' => $model->end_at == 0 ? null : date('Y-m-d H:i:s', $model->end_at)
                    ],
                ],
            ])
            ?>
        </div>
    </div>
</div>