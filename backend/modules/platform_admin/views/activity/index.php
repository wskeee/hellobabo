<?php

use common\utils\I18NUitl;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\activity\searchs\ActivitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Activities');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(I18NUitl::t('app', '{Create}{Activity}'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped table-bordered wsk-table'],
        'layout' => "{items}\n{summary}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'title',
            'code',
            [
                'attribute' => 'cover_url',
                'format' => 'raw',
                'value' => function($model){
                    return Html::img($model->cover_url,['style' => 'width:80px;']);
                }
            ],
            [
                'attribute' => 'share_poster_url',
                'format' => 'raw',
                'value' => function($model){
                    return Html::img($model->share_poster_url,['style' => 'width:80px;']);
                }
            ],
            //'share_poster_url:url',
            //'content:ntext',
            'start_time:datetime',
            'end_time:datetime',
            'status',
            //'setting:ntext',
            //'view_count',
            //'join_count',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
