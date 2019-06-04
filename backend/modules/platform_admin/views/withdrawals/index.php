<?php

use common\models\platform\searchs\WithdrawalsSearch;
use common\models\platform\Withdrawals;
use common\utils\I18NUitl;
use common\widgets\btnloader\BtnLoaderAsset;
use kartik\growl\GrowlAsset;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

GrowlAsset::register($this);
BtnLoaderAsset::register($this);

/* @var $this View */
/* @var $searchModel WithdrawalsSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', 'Withdrawals');
$this->params['breadcrumbs'][] = $this->title;

$statusColors = [
    Withdrawals::STATUS_APPLYING => 'wsk-color-primary', //'wsk-color-default',
    Withdrawals::STATUS_CHECK_FAILED => 'wsk-color-danger',
    Withdrawals::STATUS_CHECK_SUCCESS => 'wsk-color-success',
    Withdrawals::STATUS_PAY_FAILED => 'wsk-color-danger',
    Withdrawals::STATUS_PAY_SUCCESS => 'wsk-color-success',
];
?>
<div class="withdrawals-index">

    <?php  echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p class="btn-box">
        <!-- 审核 -->
        <?=
        Html::button(I18NUitl::t('app', '{Check}'), [
            'class' => 'btn btn-primary',
            'data-toggle' => "modal",
            'data-target' => "#check-modal"
        ]);
        ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped table-bordered wsk-table'],
        'layout' => "{items}\n{summary}\n{pager}",
        //'filterModel' => $searchModel,
        'columns' => [
                ['class' => 'yii\grid\CheckboxColumn',],
            'order_sn',
            'amount',
            //'need_check',
            [
                'attribute' => 'checker.nickname',
                'label' => Yii::t('app', 'Verifier'),
                'value' => function($model){
                    return empty($model->check_user_id) ? '系统' : $model->checker->nickname;
                },
            ],
            //
            [
                'attribute' => 'check_at',
                'headerOptions' => ['style' => 'width:80px;'],
                'value' => function($model) {
                    return date('Y-m-d H:i:s', $model->check_at);
                },
            ],
            'check_feedback',
            'tran_sn',
            //'pay_account',
            'pay_realname',
            //'pay_code',
            [
                'attribute' => 'pay_at',
                'headerOptions' => ['style' => 'width:80px;'],
                'value' => function($model) {
                    return date('Y-m-d H:i:s', $model->pay_at);
                },
            ],
            'pay_error_code',
            //
            [
                'attribute' => 'status',
                'headerOptions' => ['style' => 'width:70px;'],
                'contentOptions' => function($model) use($statusColors) {
                    return ['class' => $statusColors[$model->status]];
                },
                'value' => function($model) {
                    return Withdrawals::$statusNameMap[$model->status];
                },
            ],
            //'des',
            [
                'attribute' => 'created_at',
                'headerOptions' => ['style' => 'width:80px;'],
                'value' => function($model) {
                    return date('Y-m-d H:i:s', $model->created_at);
                },
            ],
        //'updated_at',
        ],
    ]);
    ?>
</div>
<!-- 解决 -->
<?= $this->render('_modal_check', ['url' => 'check']) ?>

<script>
    window.onload = function () {
        //清空历史
        $('.modal').on('show.bs.modal', function () {
            $('.modal').find('select').val(null).trigger('change');
        });
        //按钮事件
        $('.btn-box button').on('click', function () {
            if ($('.wsk-table tbody input[type=checkbox]:checked').length == 0) {
                $.notify({message: '请选择！'}, {type: 'warning'});
                return false;
            }
        });
        //设置Loader获取数据的方式
        window.webservice.BtnLoader.init({
            dataProvideFun: getSelecteGoodsId,
        });
    };

    /**
     * 获取选中
     * @returns {array|null}
     */
    function getSelecteGoodsId() {
        //获取所有已选择ID
        var ids = [];
        $checks = $('.wsk-table tbody input[type=checkbox]:checked');
        $.each($checks, function (index, item) {
            ids.push($(item).val());
        }
        );
        return ids.length == 0 ? null : {ids: ids};
    }
</script>
