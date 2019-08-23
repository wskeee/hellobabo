<?php

use common\models\order\WorkflowDelivery;
use common\models\system\Region;
use yii\widgets\DetailView;

/* @var $model WorkflowDelivery */
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
                    [
                        'attribute' => 'order.consignee',
                        'value' => function($model) {
                            return "{$model->order->consignee} ({$model->order->phone})";
                        }
                    ],
                    [
                        'attribute' => 'address',
                        'value' => implode('', Region::getRegionName([$model->province, $model->city, $model->district])) . $model->address
                    ],
                    'order.user_note',
                    [
                        'attribute' => 'created_at',
                        'value' => $model->created_at == 0 ? null : date('Y-m-d H:i:s', $model->created_at)
                    ],
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
                        'value' => WorkflowDelivery::$statusNameMap[$model->status]
                    ],
                    [
                        'attribute' => 'worker_id',
                        'value' => $model->worker_id == 0 ? null : $model->worker->nickname
                    ],
                    [
                        'attribute' => 'end_at',
                        'value' => $model->end_at == 0 ? null : date('Y-m-d H:i:s', $model->end_at)
                    ],
                    [
                        'attribute' => 'send_type',
                        'value' => WorkflowDelivery::$sendTypeNameMap[$model->send_type]
                    ],
                    'shipping_name',
                    'invoice_no',
                    'note',
                ],
            ])
            ?>
        </div>
    </div>
</div>