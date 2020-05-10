<?php

use common\models\goods\Goods;
use common\models\goods\searchs\GoodsSceneSearch;
use common\utils\I18NUitl;
use common\widgets\grid\GridViewChangeSelfColumn;
use common\widgets\layer\LayerAsset;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel GoodsSceneSearch */
/* @var $dataProvider ActiveDataProvider */
/* @var $goodsModel Goods */
/* @var $materials array */

LayerAsset::register($this);

$this->title = I18NUitl::t('app', '{Scene}{List}');
$this->params['breadcrumbs'][] = ['label' => $goodsModel->goods_name, 'url' => ['/goods_admin/goods/view', 'id' => $goodsModel->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-scene-index">

    <div class="alert alert-danger" role="alert">
        需要为每个精灵建所有场景，包括开始，结束
    </div>

    <div class="wsk-panel">
        <div class="title">
            <?= Html::a(I18NUitl::t('app', '{Create}{Scene}'), ['create', 'goods_id' => $goodsModel->id], ['class' => 'btn btn-success']) ?>
            <?= $this->render('_search', ['model' => $searchModel, 'goodsModel' => $goodsModel, 'materials' => $materials]) ?>
        </div>
        <div class="body">
            <?=
            GridView::widget([
                'tableOptions' => ['class' => 'table table-striped table-bordered wsk-table'],
                'layout' => "{items}\n{summary}\n{pager}",
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn', 'headerOptions' => ['style' => 'width:60px']],
                    'name',
                    'des:text',
                    [
                        'attribute' => 'material_value_id',
                        'headerOptions' => ['style' => 'width:100px'],
                        'value' => function ($model)use($materials) {
                            return $model->material_value_id ? $materials[$model->material_value_id] : '--';
                        }
                    ],
                    [
                        'attribute' => 'effect_url',
                        'label' => '场景效果图',
                        'format' => 'raw',
                        'headerOptions' => ['style' => 'width:90px'],
                        'value' => function ($model) {
                            return Html::img($model->effect_url, ['style' => 'height:64px']);
                        }
                    ],
                    [
                        'attribute' => 'page_effect_url',
                        'format' => 'raw',
                        'headerOptions' => ['style' => 'width:90px'],
                        'value' => function ($model) {
                            return Html::img($model->page_effect_url, ['style' => 'height:64px']);
                        }
                    ],
                    [
                        'attribute' => 'pose_url',
                        'format' => 'raw',
                        'headerOptions' => ['style' => 'width:90px'],
                        'value' => function ($model) {
                            return Html::img($model->pose_url, ['style' => 'height:64px']);
                        }
                    ],
                    /*
                      [
                      'attribute' => 'demo_url',
                      'format' => 'raw',
                      'headerOptions' => ['style' => 'width:100px'],
                      'value' => function($model) {
                      return Html::img($model->demo_url, ['style' => 'height:64px']);
                      }
                      ],
                    [
                        'attribute' => 'source_url',
                        'format' => 'raw',
                        'headerOptions' => ['style' => 'width:74px'],
                        'value' => function($model) {
                            return Html::img($model->source_url, ['style' => 'height:64px']);
                        }
                    ],*/

                    [
                        'attribute' => 'is_demo',
                        'class' => GridViewChangeSelfColumn::class,
                        'headerOptions' => ['style' => 'width:60px'],
                        'plugOptions' => [
                            'url' => '/goods_admin/scene/change-value'
                        ],
                    ],
                    [
                        'attribute' => 'is_selected',
                        'class' => GridViewChangeSelfColumn::class,
                        'headerOptions' => ['style' => 'width:80px'],
                        'plugOptions' => [
                            'url' => '/goods_admin/scene/change-value'
                        ],
                    ],
                    [
                        'attribute' => 'immutable',
                        'class' => GridViewChangeSelfColumn::class,
                        'headerOptions' => ['style' => 'width:80px'],
                        'plugOptions' => [
                            'url' => '/goods_admin/scene/change-value'
                        ],
                    ],
                    [
                        'attribute' => 'page_is_required',
                        'class' => GridViewChangeSelfColumn::class,
                        'headerOptions' => ['style' => 'width:80px'],
                        'plugOptions' => [
                            'url' => '/goods_admin/scene/change-value'
                        ],
                    ],
                    [
                        'attribute' => 'sort_order',
                        'class' => GridViewChangeSelfColumn::class,
                        'headerOptions' => ['style' => 'width:60px'],
                        'plugOptions' => [
                            'type' => 'input',
                            'url' => '/goods_admin/scene/change-value'
                        ],
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'buttons' => [
                            /*'setting' => function ($url, $model) {
                                return Html::a(I18NUitl::t('app', '{Scene}{Page}'), ['/goods_admin/scene-page/index', 'scene_id' => $model->id], ['class' => 'btn btn-primary']);
                            },*/
                            'pose' => function ($url, $model) {
                                $backurl = Yii::$app->request->url;
                                return Html::a(Yii::t('yii', 'Pose'), ['/goods_admin/page-pose/update', 'id' => $model->page_id , 'backurl' => $backurl], ['class' => 'btn btn-default', 'data-toggle' => 'wsk-modal']);
                            },
                            'updata' => function ($url, $model) {
                                return Html::a(Yii::t('yii', 'Update'), ['/goods_admin/scene/update', 'id' => $model->id], ['class' => 'btn btn-default']);
                            },
                            'delete' => function ($url, $model) {
                                return Html::a(Yii::t('yii', 'Delete'), ['/goods_admin/scene/delete', 'id' => $model->id], ['class' => 'btn btn-danger',
                                    'data' => [
                                        'pjax' => 0,
                                        'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                        'method' => 'post',
                                    ],
                                ]);
                            },
                        ],
                        'headerOptions' => ['style' => 'width:200px'],
                        'template' => '{pose} {updata} {delete}',
                    ],
                ],
            ]);
            ?>
        </div>
    </div>
</div>
<!-- 加入弹出模态框 -->
<?= $this->render('../layouts/modal') ?>
<script>
    $(function(){
        initViewer();
    });
    /**
     * 图片预览
     *
     * @returns {void}
     */
    function initViewer() {
        $('.goods-scene-index img').on('click',function(){
            layer.open({
                type: 1,
                area: ['600px', '700px'], //宽高
                shadeClose: true, //开启遮罩关闭
                content: '<div style="display: flex;justify-content: center;align-items: center;"><img src="'+$(this).attr('src')+'" style="height: 600px;" /></div>'
            });
        });
    }
</script>
