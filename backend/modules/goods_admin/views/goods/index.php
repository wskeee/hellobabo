<?php

use common\models\goods\searchs\GoodsSearch;
use common\utils\I18NUitl;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel GoodsSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = I18NUitl::t('app', '{Goods}{List}');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-index">

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(I18NUitl::t('app', '{Create}{Goods}'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped table-bordered wsk-table'],
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'goods_sn',
            'cover_url:url',
            'goods_name',
            'owner_id',
            'status',
            'created_at',
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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
