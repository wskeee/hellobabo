<?php

use common\models\goods\Goods;
use common\models\order\Coupon;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\order\Coupon */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Coupons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="coupon-view">

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            //'icon_url:url',
            'des',
            [
                'attribute' => 'used',
                'value' => Coupon::$usedNames[$model->used],
            ],
            [
                'attribute' => 'type',
                'value' => Coupon::$typeNames[$model->type],
            ],
            //'with_special',
            [
                'attribute' => 'with_id',
                'value' => function($model){
                    $goods = empty($model->with_id) ? null : Goods::findOne(['id' => $model->with_id]);
                    return $goods ? $goods->goods_name : '全场通用';
                }
            ],
            'with_amount',
            [
                'attribute' => 'used_amount',
                'value' => function($model){
                    return $model->used_amount > 1 ? "{$model->used_amount}元" : $model->used_amount*100 . '折';
                }
            ],
            'quota',
            'take_count',
            'used_count',
            [
                'attribute' => '发放时间',
                'value' => function($model){
                    return "{$model->start_time} / {$model->end_time}";
                }
            ],
            [
                'attribute' => 'valid_type',
                'value' => Coupon::$validTypeNames[$model->valid_type],
            ],
            [
                'attribute' => '有效期',
                'value' => function($model){
                    $is_absolute = $model->valid_type == Coupon::VALID_TYPE_ABSOLUTE;
                    return $is_absolute ? "{$model->valid_start_time} / {$model->valid_end_time}" : "领取 {$model->valid_days} 天后过期";
                }
            ],
            [
                'attribute' => 'status',
                'value' => Coupon::$statusNames[$model->status],
            ],
            'created_by',
            'updated_by',
            [
                'attribute' => 'created_at',
                'value' => date('Y-m-d H:i:s',$model->created_at),
            ],
            [
                'attribute' => 'updated_at',
                'value' => date('Y-m-d H:i:s',$model->updated_at),
            ],
        ],
    ]) ?>

</div>
