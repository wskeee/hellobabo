<?php

use common\models\platform\searchs\ExpressSearch;
use common\utils\I18NUitl;
use common\widgets\grid\GridViewChangeSelfColumn;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel ExpressSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', 'Expresses');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="express-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(I18NUitl::t('app', '{Create}{Express}'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped table-bordered wsk-table'],
        'layout' => "{items}\n{summary}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','headerOptions' => ['style' => 'width:50px;']],
            [
                'attribute' => 'code',
                'headerOptions' => ['style' => 'width:120px;'],
            ],
            [
                'attribute' => 'name',
                'headerOptions' => ['style' => 'width:120px;'],
            ],
            [
                'attribute' => 'logo_url',
                'headerOptions' => ['style' => 'width:64px;'],
                'format' => 'raw',
                'value' => function($model) {
                    return Html::img($model->logo_url, ['style' => 'width:32px;height:32px;']);
                }
            ],
            'des',
            //'config:ntext',
            [
                'attribute' => 'is_del',
                'class' => GridViewChangeSelfColumn::class,
                'headerOptions' => ['style' => 'width:100px'],
                'plugOptions' => [
                    'labels' => ['禁用', '启用'],
                    'values' => [1, 0],
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
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
                'template' => '{updata} {delete}',
            ],
        ],
    ]);
    ?>
</div>
