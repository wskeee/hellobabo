<?php

use common\models\goods\searchs\ShootingFaceSearch;
use common\utils\I18NUitl;
use common\widgets\grid\GridViewChangeSelfColumn;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel ShootingFaceSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', 'Shooting Faces');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shooting-face-index">

    <p>
        <?= Html::a(I18NUitl::t('app', '{Create} {Shooting Face}'), ['create'], ['class' => 'btn btn-success']) ?>
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
