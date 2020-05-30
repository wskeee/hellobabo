<?php

use common\models\order\Coupon;
use common\utils\I18NUitl;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\order\searchs\CouponSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Coupons');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(I18NUitl::t('app', '{Create}{Coupon}'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped table-bordered wsk-table'],
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'used',
                'value' => function ($model) {
                    return Coupon::$usedNames[$model->used];
                }
            ],
            [
                'attribute' => 'type',
                'value' => function ($model) {
                    return Coupon::$typeNames[$model->type];
                }
            ],
            'title',
            [
                'attribute' => 'icon_url',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::img($model->icon_url, ['width' => '100px;']);
                }
            ],
            'with_amount',
            'used_amount',
            'quota',
            'take_count',
            'used_count',
            'start_time',
            'end_time',
            'valid_start_time',
            [
                'attribute' => 'valid_end_time',
                'value' => function ($model) {
                    if($model->valid_type == Coupon::VALID_TYPE_ABSOLUTE){
                        return $model->valid_end_time;
                    }else{
                        return "领用{$model->valid_days}后";
                    }
                }
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return Coupon::$statusNames[$model->status];
                }
            ],
            'des',
            //'with_special',
            //'with_id',
            //'with_amount',
            //'used_amount',
            //'quota',
            //'take_count',
            //'used_count',
            //'start_time:datetime',
            //'end_time:datetime',
            //'valid_type',
            //'valid_start_time:datetime',
            //'valid_end_time:datetime',
            //'valid_days',
            //'status',
            //'created_by',
            //'updated_by',
            //'created_at',
            //'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'updata' => function ($url, $model) {
                        return Html::a(Yii::t('yii', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-default','data-toggle' => 'wsk-modal']);
                    },
                    'swap' => function ($url, $model) {
                        return Html::a(I18NUitl::t('app', '{Swap}{Code}'), ['/order_admin/coupon-swap/index', 'coupon_id' => $model->id], ['class' => 'btn btn-default']);
                    },
                ],
                'headerOptions' => ['style' => 'width:150px'],
                'template' => '{updata} {swap}',
            ],
        ],
    ]); ?>
</div>
