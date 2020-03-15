<?php

use common\utils\I18NUitl;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\goods\searchs\GoodsScenePageMaterialItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = ['label' => I18NUitl::t('app', 'Goods Scene Pages'), 'url' => ['/goods_admin/scene-page/index', 'scene_id' => $searchModel->scenePage->scene_id]];
$this->title = I18NUitl::t('app', "{Page Material Items}:{$searchModel->scenePage->scene->name}_{$searchModel->scenePage->name}");
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="goods-scene-page-material-item-index">

        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <p>
            <?= Html::a(I18NUitl::t('app', '{Add}{Material}'), ['create', 'scene_page_id' => $searchModel->scene_page_id],
                ['class' => 'btn btn-success', 'data-toggle' => 'wsk-modal']) ?>
        </p>

        <?= GridView::widget([
            'tableOptions' => ['class' => 'table table-striped table-bordered wsk-table'],
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'label' => '素材项名称',
                    'value' => function ($model) {
                        return $model->materialValueItem->name;
                    }
                ],
                [
                    'label' => '素材项图片',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::img($model->materialValueItem->effect_url,['style' => 'height:100px']);
                    }
                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'buttons' => [
                        'updata' => function ($url, $model) {
                            return Html::a(Yii::t('yii', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-default', 'data-toggle' => 'wsk-modal']);
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