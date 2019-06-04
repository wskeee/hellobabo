<?php

use common\models\platform\Issue;
use common\models\platform\searchs\IssueSearch;
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
/* @var $searchModel IssueSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = I18NUitl::t('app', '{Issue}{List}');
$this->params['breadcrumbs'][] = $this->title;

//指派状态颜色
$resultColors = [
    Issue::RESULT_YES => 'wsk-color-default',
    Issue::RESULT_NO => 'wsk-color-danger',
];
?>
<div class="issue-index">

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <p class="btn-box">
        <!-- 处理 -->
        <?=
        Html::button(Yii::t('app', 'Handle'), [
            'class' => 'btn btn-success',
            'data-toggle' => "modal",
            'data-target' => "#issue-modal"
        ]);
        ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-striped table-bordered wsk-table'],
        'layout' => "{items}\n{summary}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn', 'headerOptions' => ['style' => 'width:40px;'],],
            [
                'attribute' => 'type',
                'headerOptions' => ['style' => 'width:65px;'],
                'value' => function($model) {
                    return Issue::$typeNameMap[$model->type];
                }
            ],
            [
                'attribute' => 'order_sn',
                'headerOptions' => ['style' => 'width:100px;'],
                'value' => function($model) {
                    return $model->order_sn;
                }
            ],
            [
                'attribute' => 'content',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::tag('span', $model->content, ['class' => 'multi-line-clamp', 'title' => $model->content]);
                }
            ],
            //反馈人
            [
                'attribute' => 'created_by',
                'headerOptions' => ['style' => 'width:60px;'],
                'value' => function($model) {
                    return "{$model->contact_name}\n{$model->contact_phone}";
                }
            ],
            //反馈时间
            [
                'attribute' => 'created_at',
                'headerOptions' => ['style' => 'width:80px;'],
                'value' => function($model) {
                    return date('Y-m-d H:i:s', $model->created_at);
                }
            ],
            //状态
            [
                'attribute' => 'status',
                'headerOptions' => ['style' => 'width:60px;'],
                'value' => function($model) {
                    return Issue::$statusNameMap[$model->status];
                }
            ],
            //处理结果
            [
                'attribute' => 'result',
                'headerOptions' => ['style' => 'width:80px;'],
                'contentOptions' => function($model) use($resultColors) {
                    return ['class' => $resultColors[$model->result]];
                },
                'value' => function($model) {
                    return $model->status ? Issue::$resultNameMap[$model->result] : '-';
                }
            ],
            //处理时间
            [
                'attribute' => 'handler.nickname',
                'headerOptions' => ['style' => 'width:80px;'],
                'label' => $searchModel->getAttributeLabel('handled_by'),
            ],
            //处理时间
            [
                'attribute' => 'handled_at',
                'headerOptions' => ['style' => 'width:80px;'],
                'value' => function($model) {
                    return date('Y-m-d H:i:s', $model->handled_at);
                }
            ],
            [
                'attribute' => 'feedback',
                'label' => I18NUitl::t('app', '{Handle}{Feedback}'),
                'format' => 'raw',
                'value' => function($model) {
                    return Html::tag('span', $model->feedback, ['class' => 'multi-line-clamp', 'title' => $model->feedback]);
                }
            ],
        ],
    ]);
    ?>

</div>

<!-- 解决 -->
<?= $this->render('_modal_solve', ['url' => 'handle']) ?>

<script>
    window.onload = function () {
        //清空历史
        $('.modal').on('show.bs.modal', function () {
            $('.modal').find('select').val('');
        });
        //按钮事件
        $('.btn-box button').on('click', function () {
            if ($('.wsk-table tbody input[type=checkbox]:checked').length === 0) {
                $.notify({message: '请选择需要操作的设备！'}, {type: 'warning'});
                return false;
            }
        });
        //设置Loader获取数据的方式
        window.webservice.BtnLoader.init({
            dataProvideFun: getSelecteGoodsId
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
        });
        return ids.length === 0 ? null : {ids: ids};
    }
</script>
