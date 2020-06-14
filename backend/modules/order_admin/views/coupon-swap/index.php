<?php

use common\models\order\CouponSwap;
use common\modules\rbac\components\ResourceHelper;
use common\utils\I18NUitl;
use common\widgets\layer\LayerAsset;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\order\searchs\CouponSwapSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $remainNum integer */

LayerAsset::register($this);

$this->title = I18NUitl::t('app', "{Coupon} 【{$searchModel->coupon->title}】 {Swaps}");
$this->params['breadcrumbs'][] = ['label' => I18NUitl::t('app', 'Coupon'), 'url' => ['/order_admin/coupon/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-swap-index">

    <?php
    echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::a(I18NUitl::t('app', '{Create}{Swap}'), 'javascript:addSwap();', ['class' => 'btn btn-success']) ?>
        <span>还可以新增：<?= $remainNum ?> 张</span>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'code',
            //'condition',
            //'swap_id',
            //'is_del',
            //'created_by',
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return date('Y-m-d H:i:s', $model->created_at);
                }
            ],
            [
                'attribute' => 'created_by',
                'value' => function ($model) {
                    return $model->creator->nickname;
                }
            ],
            [
                'attribute' => 'is_swap',
                'value' => function ($model) {
                    return CouponSwap::$isSwapNames[$model->is_swap];
                }
            ],
            [
                'attribute' => 'swap_at',
                'value' => function ($model) {
                    return $model->swap_at == 0 ? '--' : date('Y-m-d H:i:s', $model->swap_at);
                }
            ],
            //'updated_at',

            [
                'label' => Yii::t('app', 'Action'),
                'headerOptions' => ['style' => 'width:160px'],
                'format' => 'raw',
                'value' => function ($model) {
                    //订单
                    $order_btn = $model->swap_id ? ResourceHelper::a(I18NUitl::t('app', '{Order}'), ['/order_admin/default/view', 'id' => $model->swap_id], ['class' => 'btn btn-default']) : '';
                    $del_btn = ResourceHelper::a(I18NUitl::t('app', '{Delete}'), ['delete', 'id' => $model->id],
                        ['class' => 'btn btn-danger', 'data-method' => 'post', 'data-confirm' => Yii::t('app', 'Are you sure you want to do this?'),]);

                    return "$del_btn $order_btn";
                }
            ],
        ],
    ]); ?>
</div>
<script>
    /**
     * 创建对换
     */
    function addSwap() {
        $url = "<?= Url::to(['create']) ?>";
        layer.prompt({title: '请输入要新增的数量', formType: 2}, function (text, index) {
            layer.close(index);
            var num = Number(text);
            if (num <= 0) {
                layer.msg('数量有误！');
                return;
            }
            var loadingIndex = layer.load(1, {
                shade: [0.1, '#fff'] //0.1透明度的白色背景
            });
            $.post($url, {
                coupon_id: "<?= $searchModel->coupon_id ?>",
                num: num,
            }, function (r) {
                if (r.code == '0') {
                    window.location.reload();
                } else {
                    layer.msg(r.msg);
                }
                layer.close(loadingIndex);
            });
        });
    }
</script>
