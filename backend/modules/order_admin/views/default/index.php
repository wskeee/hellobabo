<?php

use common\models\order\Order;
use common\models\order\searchs\OrderSearch;
use common\modules\rbac\components\ResourceHelper;
use common\widgets\btnloader\BtnLoaderAsset;
use kartik\growl\GrowlAsset;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

GrowlAsset::register($this);
BtnLoaderAsset::register($this);

/* @var $this View */
/* @var $searchModel OrderSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', 'Orders');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <p class="btn-box">
        <?=
        ResourceHelper::a(Yii::t('app', 'Invalid'), ['invalid'], [
            'class' => 'btn btn-danger',
            'data-toggle' => 'btnloader',
            'data-url' => 'invalid',
            'data-confirm' => Yii::t('app', 'Are you sure you want to do this?'),
        ])
        ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped table-bordered wsk-table'],
        'layout' => "{items}\n{summary}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn','headerOptions' => ['style' => 'width:30px;']],
            [
                'attribute' => 'order_sn',
                'headerOptions' => ['style' => 'width:120px;'],
            ],
            'goods_name',
            'spec_key_name',
            [
                'attribute' => 'order_amount',
                'format' => 'currency',
                'headerOptions' => ['style' => 'width:80px;'],
            ],
            [
                'attribute' => 'order_status',
                'headerOptions' => ['style' => 'width:80px;'],
                'value' => function($model) {
                    return Order::$orderStatusNameMap[$model->order_status];
                }
            ],
            [
                'attribute' => 'creater.nickname',
                'headerOptions' => ['style' => 'width:80px;'],
                'label' => $searchModel->getAttributeLabel('created_by'),
                'value' => function($model) {
                    return $model->creater->nickname;
                }
            ],
            [
                'attribute' => 'created_at',
                'headerOptions' => ['style' => 'width:80px;'],
                'value' => function($model) {
                    return date('Y-m-d H:i:s', $model->created_at);
                }
            ],
            [
                'attribute' => 'consignee',
                'headerOptions' => ['style' => 'width:80px;'],
            ],
            'user_note',
            //'goods_price',
            //'goods_num',
            //'spec_id',
            //'spec_key',
            //'work_status',
            //'pay_code',
            //'pay_sn',
            //'pay_at',
            //'init_at',
            //'upload_finish_at',
            //'design_at',
            //'print_at',
            //'shipping_at',
            //'confirm_at',
            //'zipcode',
            //'phone',
            //'country',
            //'province',
            //'city',
            //'district',
            //'town',
            //'address',
            //'is_recommend',
            //'recommend_by',
            //'updated_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a(Yii::t('yii', 'View'), ['view', 'id' => $model->id], ['class' => 'btn btn-default']);
                    },
                ],
                'headerOptions' => ['style' => 'width:80px'],
                'template' => '{view}',
            ],
        ],
    ]);
    ?>
</div>

<script>
    window.onload = function () {
        //清空历史
        $('.modal').on('show.bs.modal', function () {
            $('.modal').find('select').val(null).trigger('change');
        });
        //设置Loader获取数据的方式
        window.webservice.BtnLoader.init({
            dataProvideFun: function () {
                //获取所有已选择ID
                var ids = [];
                $checks = $('.wsk-table tbody input[type=checkbox]:checked');
                $.each($checks, function (index, item) {
                    ids.push($(item).val());
                }
                );
                return ids.length == 0 ? null : {ids: ids};
            }
        });
    };
</script>