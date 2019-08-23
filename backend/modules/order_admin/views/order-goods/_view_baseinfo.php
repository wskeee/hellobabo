<?php

use common\models\order\OrderGoods;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $model OrderGoods */
?>
<div class="wsk-panel">
    <div class="title">基本信息</div>
    <div class="body order-baseinfo">
        <div style="display: flex;">
            <div style="flex:1;margin-right: 10px;">
                <?=
                DetailView::widget([
                    'options' => ['class' => 'table table-striped table-bordered wsk-table-detail'],
                    'model' => $model,
                    'attributes' => [
                        'order_sn',
                        'goods_name',
                        [
                            'attribute' => 'goods_img',
                            'format' => 'raw',
                            'value' => function($model) {
                                return Html::img($model->goods_img, ['style' => 'width:80px;']);
                            }
                        ],
                        'goods_price',
                        'goods_cost',
                        'goods_num',
                        [
                            'attribute' => 'user_cover_url',
                            'format' => 'raw',
                            'value' => function($model) {
                                return Html::img($model->user_cover_url, ['style' => 'width:120px;']);
                            }
                        ],
                    ],
                ])
                ?>
            </div>
            <div style="flex:1;">
                <?=
                DetailView::widget([
                    'options' => ['class' => 'table table-striped table-bordered wsk-table-detail'],
                    'model' => $model,
                    'attributes' => [
                        'spec_key_name',
                        'scene_num',
                        [
                            'attribute' => 'status',
                            'value' => OrderGoods::$statusNameMap[$model->status]
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
                            'attribute' => 'design_at',
                            'value' => $model->design_at == 0 ? null : date('Y-m-d H:i:s', $model->design_at)
                        ],
                        [
                            'attribute' => 'print_at',
                            'value' => $model->design_at == 0 ? null : date('Y-m-d H:i:s', $model->design_at)
                        ],
                    ],
                ])
                ?>
            </div>
        </div>
    </div>
</div>