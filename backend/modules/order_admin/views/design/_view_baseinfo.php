<?php

    use common\models\order\Order;
    use common\models\system\Region;
    use yii\widgets\DetailView;
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
                    'id',
                    'order_sn',
                    'goods_name',
                    'spec_key_name',
                    'order_amount',
                    [
                        'attribute' => 'order_status',
                        'value' => function($model) {
                            return Order::$orderStatusNameMap[$model->order_status];
                        }
                    ],
                    [
                        'attribute' => 'consignee',
                        'value' => function($model) {
                            return "$model->consignee ($model->phone)";
                        }
                    ],
                    [
                        'attribute' => 'address',
                        'value' => function($model) {
                            return implode('', Region::getRegionName([$model->province, $model->city, $model->district])) . $model->address;
                        }
                    ],
                    'user_note',
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
                    'pay_sn',
                    [
                        'attribute' => 'pay_at',
                        'value' => $model->pay_at == 0 ? null : date('Y-m-d H:i:s', $model->pay_at)
                    ],
                    [
                        'attribute' => 'init_at',
                        'value' => $model->init_at == 0 ? null : date('Y-m-d H:i:s', $model->init_at)
                    ],
                    [
                        'attribute' => 'upload_finish_at',
                        'value' => $model->upload_finish_at == 0 ? null : date('Y-m-d H:i:s', $model->upload_finish_at)
                    ],
                    [
                        'attribute' => 'shipping_at',
                        'value' => $model->shipping_at == 0 ? null : date('Y-m-d H:i:s', $model->shipping_at)
                    ],
                    [
                        'attribute' => 'confirm_at',
                        'value' => $model->confirm_at == 0 ? null : date('Y-m-d H:i:s', $model->confirm_at)
                    ],
                    [
                        'attribute' => 'recommend_by',
                        'value' => function($model) {
                            return $model->is_recommend ? $model->referrer->nickname : null;
                        }
                    ],
                    "creater.nickname:text:{$model->getAttributeLabel('created_by')}",
                    [
                        'attribute' => 'created_at',
                        'value' => $model->created_at == 0 ? null : date('Y-m-d H:i:s', $model->created_at)
                    ],
                ],
            ])
            ?>
        </div>
    </div>
</div>