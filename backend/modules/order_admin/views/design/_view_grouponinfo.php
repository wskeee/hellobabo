<?php

use common\models\order\OrderGoods;
use common\models\order\WorkflowDesign;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $model WorkflowDesign */
$relateOrderGoods = OrderGoods::find()->where(['groupon_id' => $model->orderGoods->groupon_id])->with('order')->all();
$relateWorkflowDesign = ArrayHelper::map(WorkflowDesign::find()->where(['order_goods_id' => ArrayHelper::getColumn($relateOrderGoods, 'id')])->all(), 'order_goods_id', 'id');
?>
<div class="wsk-panel goods-info">
    <div class="title">
        团购信息
    </div>
    <div class="body">
        <?=
        GridView::widget([
            'dataProvider' => new ArrayDataProvider(['allModels' => $relateOrderGoods]),
            // 'filterModel' => new OrderGoodsMaterial(),
            'layout' => "{items}",
            'columns' => [
                [
                    'attribute' => 'order.consignee',
                    'headerOptions' => ['style' => 'width:120px;'],
                    'value' => function($model) {
                        return $model->order->consignee . " ({$model->order->phone})";
                    }
                ],
                [
                    'attribute' => 'status',
                    'headerOptions' => ['style' => 'width:120px;'],
                    'value' => function($model) {
                        return OrderGoods::$statusNameMap[$model->status];
                    }
                ],
                [
                    'label' => Yii::t('app', 'Action'),
                    'headerOptions' => ['style' => 'width:120px;'],
                    'format' => 'raw',
                    'value' => function($model)use($relateWorkflowDesign) {
                        $designId = isset($relateWorkflowDesign[$model->id]) ? $relateWorkflowDesign[$model->id] : null;
                        return $designId ? Html::a(Yii::t('app', 'View'), "/order_admin/design/view?id={$designId}", ['class' => 'btn btn-default','target'=>'_blank']) : '<span style="color:#aaa">未准备</span>';
                    }
                ],
            ],
        ]);
        ?>
    </div>
</div>