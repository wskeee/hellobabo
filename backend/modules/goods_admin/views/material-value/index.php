<?php

use common\models\goods\GoodsMaterial;
use common\models\goods\searchs\GoodsMaterialValueSearch;
use common\utils\I18NUitl;
use common\widgets\grid\GridViewChangeSelfColumn;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel GoodsMaterialValueSearch */
/* @var $dataProvider ActiveDataProvider */
/* @var $materialModel GoodsMaterial */

$this->title = I18NUitl::t('app', '{Material}{Value}{Config}：{Name}', ['Name' => $materialModel->name]);
$this->params['breadcrumbs'][] = ['label' => $materialModel->goods->goods_name, 'url' => ['/goods_admin/goods/view', 'id' => $materialModel->goods_id]];
$this->params['breadcrumbs'][] = ['label' => I18NUitl::t('app', '{Material}{List}'), 'url' => ['/goods_admin/material/index', 'goods_id' => $materialModel->goods_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="goods-material-value-index">

        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <!--属性值-->
        <div class="wsk-panel pull-left">

            <div class="title">
                <div class="pull-left">
                    <?= I18NUitl::t('app', '{Material}{Value}{List}') ?>
                </div>

                <div class="btngroup pull-right">
                    <span class="loading" style="display: none;"></span>
                    <?= Html::a(Yii::t('app', 'Add'), ['material-value/create', 'material_id' => $materialModel->id], ['class' => 'btn btn-primary btn-flat', 'data-toggle' => 'wsk-modal']); ?>
                </div>
            </div>

            <div class="body">
                <?=
                GridView::widget([
                    'tableOptions' => ['class' => 'table table-striped table-bordered wsk-table'],
                    'dataProvider' => $dataProvider,
                    //'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn', 'headerOptions' => ['style' => 'width:60px;']],
                        'name',
                        [
                            'attribute' => 'effect_url',
                            'format' => 'raw',
                            'headerOptions' => ['style' => 'width:100px'],
                            'value' => function ($model) {
                                return Html::img($model->effect_url, ['style' => 'height:64px']);
                            }
                        ],
                        [
                            'attribute' => 'source_url',
                            'format' => 'raw',
                            'headerOptions' => ['style' => 'width:100px'],
                            'value' => function ($model) {
                                return Html::img($model->source_url, ['style' => 'height:64px']);
                            }
                        ],
                        [
                            'attribute' => 'is_selected',
                            'class' => GridViewChangeSelfColumn::class,
                            'headerOptions' => ['style' => 'width:80px'],
                            'plugOptions' => [
                                'url' => '/goods_admin/material-value/change-value'
                            ],
                        ],
                        [
                            'attribute' => 'sort_order',
                            'class' => GridViewChangeSelfColumn::class,
                            'headerOptions' => ['style' => 'width:100px'],
                            'plugOptions' => [
                                'type' => 'input',
                                'url' => '/goods_admin/material-value/change-value'
                            ],
                        ],
                        'des',
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'buttons' => [
                                'view' => function ($url, $model) {
                                    return Html::a(I18NUitl::t('app', '{Material}{Item}'), ['/goods_admin/material-value-item/index', 'material_value_id' => $model->id], ['class' => 'btn btn-primary']);
                                },
                                'updata' => function ($url, $model) {
                                    return Html::a(Yii::t('yii', 'Update'), ['/goods_admin/material-value/update', 'id' => $model->id], ['class' => 'btn btn-default', 'data-toggle' => 'wsk-modal']);
                                },
                                'delete' => function ($url, $model) {
                                    return Html::a(Yii::t('yii', 'Delete'), ['/goods_admin/material-value/delete', 'id' => $model->id], ['class' => 'btn btn-danger',
                                        'data' => [
                                            'pjax' => 0,
                                            'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                            'method' => 'post',
                                        ],
                                    ]);
                                },
                            ],
                            'headerOptions' => ['style' => 'width:200px'],
                            'template' => '{view} {updata} {delete}',
                        ],
                    ],
                ]);
                ?>
            </div>

        </div>
    </div>
    <!-- 加入弹出模态框 -->
<?= $this->render('../layouts/modal') ?>