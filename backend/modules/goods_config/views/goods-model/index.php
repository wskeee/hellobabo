<?php

use common\models\goods\searchs\GoodsModelSearch;
use common\utils\I18NUitl;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel GoodsModelSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = I18NUitl::t('app', 'Models');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-model-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(I18NUitl::t('app', '{Create} {Goods}{Model}'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn' , 'headerOptions' => ['style' => 'width:60px']],

            'name',
            'des',

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'updata' => function ($url, $model){
                        return Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-default']);
                    },
                    'view' => function ($url, $model){
                        return Html::a(Yii::t('app', 'Config'), ['view', 'id' => $model->id], ['class' => 'btn btn-default']);
                    },
                    'delete' => function ($url, $model){
                        return Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'pjax' => 0, 
                                'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
                'headerOptions' => [
                    'style' => [
                        'width' => '200px',
                    ],
                ],
                            
                'template' => '{updata} {view} {delete}',
            ],
        ],
    ]); ?>
</div>
