<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\order\Order */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="order-view">

    <h1><?= Html::encode($this->title) ?></h1>

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
            'order_sn',
            'goods_id',
            'goods_name',
            'goods_price',
            'goods_num',
            'spec_id',
            'spec_key',
            'spec_key_name',
            'order_amount',
            'order_status',
            'work_status',
            'user_note',
            'play_code',
            'play_sn',
            'play_at',
            'init_at',
            'upload_finish_at',
            'design_at',
            'print_at',
            'shipping_at',
            'confirm_at',
            'consignee',
            'zipcode',
            'phone',
            'country',
            'province',
            'city',
            'district',
            'town',
            'address',
            'is_recommend',
            'recommend_by',
            'created_by',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
