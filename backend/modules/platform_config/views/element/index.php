<?php

use common\models\goods\GoodsElement;
use common\models\goods\searchs\GoodsElementSearch;
use common\utils\I18NUitl;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel GoodsElementSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', 'Elements');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-element-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(I18NUitl::t('app', '{Create}{Element}'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped table-bordered wsk-table'],
        'layout' => "{items}\n{summary}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'headerOptions' => ['style' => 'width:50px;']],
            [
                'attribute' => 'type',
                'headerOptions' => ['style' => 'width:50px;'],
                'value' => function($model) {
                    return GoodsElement::$typeNameMap[$model->type];
                }
            ],
            [
                'attribute' => 'name',
                'headerOptions' => ['style' => 'width:150px;']
            ],
            [
                'attribute' => 'thumb_url',
                'headerOptions' => ['style' => 'width:96px;'],
                'format' => 'raw',
                'value' => function($model) {
                    return Html::img($model->thumb_url, ['style' => 'width:64px;height:64px']);
                }],
            //'img_url:url',
            //'sound_url:url',
            //'config:ntext',
            'des',
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a(Yii::t('yii', 'View'), ['view', 'id' => $model->id], ['class' => 'btn btn-default']);
                    },
                    'updata' => function ($url, $model) {
                        return Html::a(Yii::t('yii', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-default']);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a(Yii::t('yii', 'Delete'), ['delete', 'id' => $model->id], ['class' => 'btn btn-danger',
                                    'data' => [
                                        'pjax' => 0,
                                        'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                        'method' => 'post',
                                    ],
                        ]);
                    },
                ],
                'headerOptions' => ['style' => 'width:200px'],
                'template' => '{view} {updata} {delete}',
            ],
        ],
    ]);
    ?>
</div>
