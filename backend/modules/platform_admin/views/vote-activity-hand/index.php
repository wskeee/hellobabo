<?php

use common\models\activity\searchs\VoteActivityHandSearch;
use common\models\activity\VoteActivityHand;
use common\utils\I18NUitl;
use common\widgets\btnloader\BtnLoaderAsset;
use common\widgets\viewer\ViewerAsset;
use kartik\growl\GrowlAsset;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

GrowlAsset::register($this);
BtnLoaderAsset::register($this);
ViewerAsset::register($this);

/* @var $this View */
/* @var $searchModel VoteActivityHandSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = I18NUitl::t('app', '{Vote Activity Hand}{Check}');
$this->params['breadcrumbs'][] = ['label' => $searchModel->activity->name, 'url' => ['/platform_admin/vote-activity/view', 'id' => $searchModel->activity_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vote-activity-hand-index">

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p class="btn-box">
        <!-- 处理 -->
        <?=
        Html::button(Yii::t('app', 'Check'), [
            'class' => 'btn btn-success',
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
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'headerOptions' => ['style' => 'width:40px;'],
            ],
            [
                'attribute' => 'num',
                'headerOptions' => ['style' => 'width:60px;'],
            ],
            [
                'attribute' => 'target_name',
                'headerOptions' => ['style' => 'width:120px;'],
            ],
            [
                'attribute' => 'target_age',
                'headerOptions' => ['style' => 'width:60px;'],
            ],
            [
                'attribute' => 'target_img',
                'format' => 'raw',
                'headerOptions' => ['style' => 'width:150px;'],
                'value' => function($model) {
                    return Html::img($model->target_img, ['style' => 'width:100%;']);
                }
            ],
            [
                'attribute' => 'check_status',
                'headerOptions' => ['style' => 'width:80px;'],
                'value' => function($model) {
                    return VoteActivityHand::$checkStatusNameMap[$model->check_status];
                }
            ],
            'check_feedback',
            [
                'attribute' => 'created_at',
                'headerOptions' => ['style' => 'width:80px;'],
                'value' => function($model) {
                    return date('Y-m-d H:i:s', $model->created_at);
                }
            ],
            [
                'attribute' => 'updated_at',
                'headerOptions' => ['style' => 'width:80px;'],
                'value' => function($model) {
                    return date('Y-m-d H:i:s', $model->updated_at);
                }
            ],
        ],
    ]);
    ?>
</div>
<!-- 解决 -->
<?= $this->render('_modal_solve', ['url' => '/platform_admin/vote-activity-hand/check']) ?>

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
        
        initViewer();
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
    
    /**
     * 图片预览
     * 
     * @returns {void}
     */
    function initViewer() {
        var $image = $('#image');

        $image.viewer({
            inline: false,
            viewed: function () {
                $image.viewer('zoomTo', 1);
            }
        });

        // Get the Viewer.js instance after initialized
        var viewer = $image.data('viewer');

        // View a list of images
        $('.vote-activity-hand-index').viewer();
    }
</script>
