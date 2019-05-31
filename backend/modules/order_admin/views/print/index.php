<?php

use backend\modules\order_admin\assets\OrderModuleAsset;
use common\models\order\searchs\WorkflowPrintSearch;
use common\models\order\WorkflowPrint;
use common\modules\rbac\components\ResourceHelper;
use common\utils\I18NUitl;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\web\View;

OrderModuleAsset::register($this);

/* @var $this View */
/* @var $searchModel WorkflowPrintSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', 'Prints');
$this->params['breadcrumbs'][] = $this->title;

//指派状态颜色
$assignColors = [
    WorkflowPrint::STATUS_WAIT_START => 'wsk-color-danger',
    WorkflowPrint::STATUS_RUNGING => 'wsk-color-primary',
    WorkflowPrint::STATUS_ENDED => 'wsk-color-success',
];
?>
<div class="workflow-print-index">

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p class="btn-box">

    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped table-bordered wsk-table'],
        'layout' => "{items}\n{summary}\n{pager}",
        //'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'order_sn',
                'headerOptions' => ['style' => 'width:120px;'],
            ],
            [
                'attribute' => 'order.goods_name',
                'headerOptions' => ['style' => 'width:120px;'],
            ],
            [
                'attribute' => 'order.spec_key_name',
            ],
            [
                'attribute' => 'order.consignee',
                'label' => Yii::t('app', 'Contacter'),
                'headerOptions' => ['style' => 'width:90px;'],
                'value' => function($model) {
                    return "{$model->order->consignee} ({$model->order->phone})";
                }
            ],
            'order.user_note',
            [
                'attribute' => 'status',
                'headerOptions' => ['style' => 'width:80px;'],
                'contentOptions' => function($model)use($assignColors) {
                    return ['class' => $assignColors[$model->status]];
                },
                'value' => function($model) {
                    return WorkflowPrint::$statusNameMap[$model->status];
                }
            ],
            [
                'attribute' => 'worker_id',
                'headerOptions' => ['style' => 'width:80px;'],
                'value' => function($model) {
                    return $model->worker ? $model->worker->nickname : null;
                }
            ],
            [
                'attribute' => 'start_at',
                'headerOptions' => ['style' => 'width:80px;'],
                'value' => function($model) {
                    return $model->start_at == 0 ? null : date('Y-m-d H:i:s', $model->start_at);
                }
            ],
            [
                'attribute' => 'end_at',
                'headerOptions' => ['style' => 'width:80px;'],
                'value' => function($model) {
                    return $model->end_at == 0 ? null : date('Y-m-d H:i:s', $model->end_at);
                }
            ],
            //'end_at',
            //'worker_id',
            //'created_at',
            //'updated_at',
            [
                'label' => Yii::t('app', 'Action'),
                'headerOptions' => ['style' => 'width:160px'],
                'format' => 'raw',
                'value' => function($model) {
                    //订单
                    $order_btn = ResourceHelper::a(I18NUitl::t('app', '{Order}'), ['/order_admin/default/view', 'id' => $model->order_id], ['class' => 'btn btn-default']);
                    $view_btn = ResourceHelper::a(I18NUitl::t('app', '{Design}{Detail}'), ['view', 'id' => $model->id], ['class' => 'btn btn-default']);

                    return "$view_btn $order_btn";
                }
            ],
        ],
    ]);
    ?>
</div>
