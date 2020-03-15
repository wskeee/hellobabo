<?php

use common\utils\I18NUitl;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\goods\searchs\GoodsScenePreviewSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = I18NUitl::t('app', '{Scene}{Preview}{List}');
$this->params['breadcrumbs'][] = ['label' => I18NUitl::t('app', "{$searchModel->goods->goods_name}"), 'url' => ['/goods_admin/goods/view', 'goods_id' => $searchModel->goods_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-scene-preview-index">

    <p class="btn-group">
        <?= Html::a(I18NUitl::t('app', 'Add'), ['create', 'goods_id' => $searchModel->goods_id], ['class' => 'btn btn-success', 'data-toggle' => 'wsk-modal']) ?>
    <div class="pull-right"><?php echo $this->render('_search', ['model' => $searchModel]); ?></div>
    </p>
    <?= GridView::widget([
        'tableOptions' => ['class' => 'table table-striped table-bordered wsk-table'],
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'material_value_id',
                'value' => function($model){
                    return $model->materialValue->name;
                }
            ],
            [
                'attribute' => 'scene_id',
                'value' => function($model){
                    return $model->scene->name;
                }
            ],
            [
                'attribute' => 'effect_url',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::img($model->effect_url, ['style' => 'height:80px;']);
                }
            ],
            //'is_del',

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'updata' => function ($url, $model) {
                        return Html::a(Yii::t('yii', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-default','data-toggle' => 'wsk-modal']);
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
    ]); ?>
</div>
<!-- 加入弹出模态框 -->
<?= $this->render('../layouts/modal') ?>
