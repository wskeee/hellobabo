<?php

use common\models\order\searchs\WorkflowDesignSearch;
use common\models\order\WorkflowDesign;
use common\modules\rbac\components\ResourceHelper;
use common\utils\I18NUitl;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel WorkflowDesignSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', 'Designs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="workflow-design-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

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
                'value' => function($model) {
                    return WorkflowDesign::$statusNameMap[$model->status];
                }
            ],
            [
                'attribute' => 'worker_id',
                'label' => Yii::t('app', 'Designer'),
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
