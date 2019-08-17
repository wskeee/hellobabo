<?php

use common\models\goods\searchs\ShootingActionSearch;
use common\utils\I18NUitl;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel ShootingActionSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', 'Shooting Action');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Goods Scene Pages'), 'url' => ['/goods_admin/scene-page/index', 'scene_id' => $searchModel->page->scene_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shooting-action-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(I18NUitl::t('app', '{Add} {Shooting Action}'), ['create', 'page_id' => $searchModel->page_id], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            "page.name:text:{$searchModel->getAttributeLabel('page_id')}",
            'name',
            [
                'attribute' => 'url',
                'format' => 'raw',
                'value' => function($model){
                    return Html::img($model->url, ['style' => 'height:150px;']);
                }
            ],
            'des',
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
                'headerOptions' => ['style' => 'width:220px'],
                'template' => '{updata} {delete}',
            ],
        ],
    ]);
    ?>
</div>
