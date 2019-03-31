<?php

use backend\modules\goods_admin\assets\GoodsModuleAsset;
use common\models\goods\Goods;
use common\models\goods\searchs\GoodsSearch;
use common\modules\rbac\components\ResourceHelper;
use common\utils\I18NUitl;
use kartik\growl\GrowlAsset;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

GoodsModuleAsset::register($this);
GrowlAsset::register($this);

/* @var $this View */
/* @var $searchModel GoodsSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = I18NUitl::t('app', '{Goods}{List}');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="goods-index">

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="btn-box pull-right">
        <span>&nbsp;</span>
        <div class="loading"></div>
        <?= ResourceHelper::a(I18NUitl::t('app', '{Batch}{Publish}'), ['batch-publish'], ['class' => 'btn btn-danger']) ?>
        <?= ResourceHelper::a(I18NUitl::t('app', '{Batch}{Sold Out}'), ['batch-sold-out'], ['class' => 'btn btn-danger']) ?>
    </div>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped table-bordered wsk-table'],
        'layout' => "{items}\n{summary}\n{pager}",
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn',],
            //商品编号
            [
                'attribute' => 'goods_sn',
                'headerOptions' => ['style' => 'width:100px'],
            ],
            //封面
            [
                'attribute' => 'cover_url',
                'format' => 'raw',
                'headerOptions' => ['style' => 'width:106px'],
                'value' => function($model) {
                    return Html::img($model->cover_url, ['style' => 'width:96px;height:96']);
                }
            ],
            'goods_name',
            //作者
            [
                'attribute' => 'owner.nickname',
                'label' => Yii::t('app', 'Owner'),
                'headerOptions' => ['style' => 'width:70px'],
            ],
            //状态
            [
                'attribute' => 'status',
                'headerOptions' => ['style' => 'width:70px'],
                'format' => 'raw',
                'value' => function($model) {
                    $statusText = Goods::$statusKeyMap[$model->status];
                    $color = $model->status == Goods::STATUS_SOLD_OUT ? '#ff3300' : '#666';
                    return "<span style='color:$color'>{$statusText}</span>";
                }
            ],
            [
                'attribute' => 'created_at',
                'headerOptions' => ['style' => 'width:90px'],
                'value' => function($model) {
                    return empty($model->created_at) ? null : date('Y-m-d h:i:s', $model->created_at);
                }
            ],
            'goods_des',
            'tags',
            //'category_id',
            //'model_id',
            //'goods_cost',
            //'goods_price',
            //'video_url:url',
            //'store_count',
            //'comment_count',
            //'click_count',
            //'share_count',
            //'like_count',
            //'sale_count',
            //'created_by',
            //'updated_by',
            //'updated_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function($url, $model) {
                        return Html::a(Yii::t('app', 'View'), ['view', 'id' => $model->id], ['class' => 'btn btn-default']);
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
    //加载状态
    var isPosting = false;

    window.onload = function () {
        initInput();
        initBatchBtn();
        setLoading(false);
    }

    /**
     * 初始checkbox事件
     * @returns {void}
     */
    function initInput() {
        //设置点击行选择中checkbox
        $('.wsk-table tbody tr input[type=checkbox]').on('click', function (evt) {
            evt.stopPropagation();
        });
        $('.wsk-table tbody tr .btn').on('click', function (evt) {
            evt.stopPropagation();
        });
        $('.wsk-table tbody tr').on('click', function (evt) {
            $checkbox = $(this).find('input[type=checkbox]');
            $checkbox.prop('checked', !$checkbox.prop('checked'));
        });
    }

    /**
     * 初始批量操作按钮
     * @returns {void}
     */
    function initBatchBtn() {
        //批量发布,批量下架
        $('.btn-box .btn').on('click', function () {
            if (isPosting)
                return;
            var goodsIds = getSelecteGoodsId();
            if (goodsIds.length > 0) {
                //设置加载中...
                setLoading(true);
                $.ajax({
                    url: $(this).attr('href'),
                    type: 'POST',
                    data: {goodsIds: goodsIds},
                    success: function (data, textStatus) {
                        setLoading(false);
                        if (data.code == '0') {
                            //成功
                            $.notify({message: '操作成功！'}, {type: 'success'});
                            //重新刷新页面
                            location.reload();
                        } else {
                            //错误
                            $.notify({message: '操作失败！\n' + data.msg}, {type: 'danger'});
                        }
                    },
                    error: function (e) {
                        setLoading(false);
                        $.notify({message: '网络错误！'}, {type: 'danger'});
                    }
                });
            } else {
                $.notify({message: '请选择需要操作的商品！'}, {type: 'warning'});
            }
            return false;
        });
    }

    /**
     * 获取已选择的商品ID
     * @returns {String}
     */
    function getSelecteGoodsId() {
        //获取所有已选择ID
        var goodsIds = [];
        $checks = $('.wsk-table tbody input[type=checkbox]:checked');
        $.each($checks, function (index, item) {
            goodsIds.push($(item).val());
        });
        return goodsIds.join(',');
    }
    /**
     * 设置加载状态
     * @param {bool} bo
     * @returns {void}
     */
    function setLoading(bo) {
        isPosting = bo;
        if (bo) {
            $('.loading').show();
            $('.btn-box .btn').addClass('disabled');
        } else {
            $('.loading').hide()
            $('.btn-box .btn').removeClass('disabled');
        }
    }
</script>
