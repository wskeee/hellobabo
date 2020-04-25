<?php

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

            'id',
            'title',
            'icon_url:url',
            'des',
            'used',
            //'type',
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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
