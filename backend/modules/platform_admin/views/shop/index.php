<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\shop\searchs\ShopSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Shops');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'user.nickname:text:账号',
            'name',
            'income_value',
            //'des',
            'status',
            [
                'attribute' => 'logo_url',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::img($model->logo_url, ['style' => 'height:80px']);
                }
            ],
            //'cover_url:url',
            //'goods_count',
            //'order_count',
            //'all_income',
            //'real_income',
            //'day_income',
            //'month_income',
            //'last_income_time:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
