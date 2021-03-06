<?php

use common\models\goods\searchs\ShootingAngleSearch;
use common\utils\I18NUitl;
use common\widgets\grid\GridViewChangeSelfColumn;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel ShootingAngleSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', 'Shooting Angles');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shooting-angle-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(I18NUitl::t('app', '{Create} {Shooting Angle}'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'url:image',
            'des',
            [
                'attribute' => 'is_del',
                'class' => GridViewChangeSelfColumn::class,
                'headerOptions' => ['style' => 'width:100px'],
                'plugOptions' => [
                    'labels' => ['禁用', '启用'],
                    'values' => [1, 0],
                ],
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
