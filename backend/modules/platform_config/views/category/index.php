<?php

use common\models\searchs\GoodsCategorySearch;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\LinkPager;

/* @var $this View */
/* @var $searchModel GoodsCategorySearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', '{Goods}{Category}', [
    'Goods' => Yii::t('app', 'Goods'), 'Category' => Yii::t('app', 'Category')
]);
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="goods-category-index">
  
    <p>
        <?= Html::a(Yii::t('app', '{Create}{Category}', [
            'Create' => Yii::t('app', 'Create'), 'Category' => Yii::t('app', 'Category')
        ]), ['create'], ['id' => 'btn-addCate', 'class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'layout' => "{items}\n{summary}\n{pager}",
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => [
                    'style' => [
                        'width' => '50px',
                    ],
                ],
            ],

            [
                'attribute' => 'name',
                'label' => Yii::t('app', '{Goods}{Category}{Name}', [
                    'Goods' => Yii::t('app', 'Goods'), 'Category' => Yii::t('app', 'Category'), 'Name' => Yii::t('app', 'Name')
                ]),
                'headerOptions' => [
                    'style' => [
                        'width' => '1000px',
                    ],
                ],
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'updata' => function ($url, $model){
                        return Html::a(Yii::t('yii', 'Update'), ['update', 'id' => $model->id], [
                            'id' => 'btn-updateCate', 'class' => 'btn btn-default'
                        ]);
                    },
                    'delete' => function ($url, $model){
                        return ' ' . Html::a(Yii::t('yii', 'Delete'), ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'pjax' => 0, 
                                'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
                'headerOptions' => [
                    'style' => [
                        'width' => '200px',
                    ],
                ],
                            
                'template' => '{updata}{delete}',
            ],
        ],
    ]); ?>
    
</div>

<!--加载模态框-->
<?= $this->render('/layouts/modal'); ?>

<?php
$js = <<<JS
    
    // 弹出商品类目面板
    $('#btn-addCate, #btn-updateCate').click(function(e){
        e.preventDefault();
        showModal($(this));
    });
    
JS;
    $this->registerJs($js, View::POS_READY);
?>