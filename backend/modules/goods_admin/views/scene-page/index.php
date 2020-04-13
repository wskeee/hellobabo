<?php

use common\models\goods\GoodsScenePage;
use common\models\goods\searchs\GoodsScenePageSearch;
use common\models\goods\ShootingAction;
use common\utils\I18NUitl;
use common\widgets\grid\GridViewChangeSelfColumn;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel GoodsScenePageSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', 'Goods Scene Pages');
$this->params['breadcrumbs'][] = ['label' => I18NUitl::t('app', '{Scene}{List}'), 'url' => ['/goods_admin/scene/index', 'goods_id' => $searchModel->scene->goods_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-scene-page-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(I18NUitl::t('app', '{Add} {Goods Scene Page}'), ['create', 'scene_id' => $searchModel->scene_id], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'tableOptions' => ['class' => 'table table-striped table-bordered wsk-table'],
        'layout' => "{items}\n{summary}\n{pager}",
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'headerOptions' => ['style' => 'width:60px']],
            'name',
            "scene.name:text:{$searchModel->getAttributeLabel('scene_id')}",
            [
                'attribute' => 'angle_id',
                'format' => 'raw',
                'value' => function($model) {
                    $img = Html::img($model->angle->url, ['style' => 'width:64px;']);
                    $name = Html::tag('span', $model->angle->name);
                    return Html::tag('div', "{$img}</br>{$name}");
                }
            ],
            [
                'attribute' => 'face_id',
                'format' => 'raw',
                'value' => function($model) {
                    $img = Html::img($model->face->url, ['style' => 'width:64px;']);
                    $name = Html::tag('span', $model->face->name);
                    return Html::tag('div', "{$img}</br>{$name}");
                }
            ],
            [
                'label' => Yii::t('app', 'Shooting Action'),
                'format' => 'raw',
                'value' => function($model) {
                    /* @var $action ShootingAction */
                    $actions = [];
                    foreach ($model->actions as $action) {
                        $img = Html::img($action->url, ['style' => 'width:64px;']);
                        $name = Html::tag('span', $action->name);
                        $actions[] = Html::tag('div', "{$img}</br>{$name}");
                    }
                    return Html::tag('div', implode('', $actions), ['style' => 'display: flex;justify-content: space-around;']);
                }
            ],
            [
                'attribute' => 'effect_url',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::img($model->effect_url, ['style' => 'height:200px;']);
                },
            ],
            //'source_url:url',
            [
                'attribute' => 'pos',
                'value' => function($model) {
                    return GoodsScenePage::$posNameMap[$model->pos];
                },
                'headerOptions' => ['style' => 'width:80px']
            ],
            [
                'attribute' => 'is_required',
                'class' => GridViewChangeSelfColumn::class,
                'headerOptions' => ['style' => 'width:80px']
            ],
            //'is_del',
            'des',
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'setting' => function ($url, $model) {
                        return Html::a(I18NUitl::t('app', '{Shooting Action}'), ['/goods_admin/shooting-action/index', 'page_id' => $model->id], ['class' => 'btn btn-primary', 'style' => 'margin-bottom: 5px;']);
                    },
                    'updata' => function ($url, $model) {
                        return Html::a(Yii::t('yii', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-default', 'style' => 'margin-bottom: 5px;']);
                    },
                    'pose' => function ($url, $model) {
                        return Html::a(Yii::t('yii', 'Pose'), ['/goods_admin/page-pose/update', 'id' => $model->id], ['class' => 'btn btn-default', 'style' => 'margin-bottom: 5px;','data-toggle' => 'wsk-modal']);
                    },
                    'preview' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'Preview'), null, [
                                    'class' => 'btn btn-default',
                                    'style' => 'margin-bottom: 5px;',
                                    'href' => Yii::$app->params['hellobabo']['source_preview_url'] . "?page_ids={$model->id}",
                                    'target' => '_blank',
                        ]);
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
                'headerOptions' => ['style' => 'width:120px'],
                'template' => '<div style="display:flex;flex-direction: column;">{setting} {updata} {pose} {preview} {delete}</div>',
            ],
        ],
    ]);
    ?>
</div>
<!-- 加入弹出模态框 -->
<?= $this->render('../layouts/modal') ?>
