<?php

use common\models\activity\searchs\VoteActivitySearch;
use common\utils\I18NUitl;
use common\widgets\grid\GridViewChangeSelfColumn;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel VoteActivitySearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', 'Vote Activities');
?>
<div class="vote-activity-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(I18NUitl::t('app', '{Create} {Vote Activity}'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped table-bordered wsk-table'],
        'layout' => "{items}\n{summary}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'start_time',
            'end_time',
            [
                'class' => GridViewChangeSelfColumn::class,
                'attribute' => 'is_publish',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function($url, $model) {
                        return Html::a(Yii::t('app', 'View'), ['view', 'id' => $model->id], ['class' => 'btn btn-default']);
                    },
                ],
                'headerOptions' => ['style' => 'width:80px'],
                'template' => '{view}',
            ],
        ],
    ]);
    ?>
</div>
