<?php

use backend\modules\order_admin\assets\OrderModuleAsset;
use common\models\order\OrderGoods;
use common\models\order\searchs\OrderGoodsSearch;
use common\modules\rbac\components\ResourceHelper;
use common\utils\I18NUitl;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

OrderModuleAsset::register($this);

/* @var $this View */
/* @var $searchModel OrderGoodsSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = I18NUitl::t('app', '{Order} {Goods}');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-goods-index">

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <p class="btn-box">
        
    </p>

    <?=
    GridView::widget([
        'tableOptions' => ['class' => 'table table-striped table-bordered wsk-table'],
        'layout' => "{items}\n{summary}\n{pager}",
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'order_sn',
            'goods_name',
            [
                'attribute' => 'goods_img',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::img($model->goods_img, ['style' => 'width:60px;']);
                }
            ],
            'goods_price',
            //'goods_cost',
            //'goods_num',
            'user_cover_url:url',
            //'spec_id',
            //'spec_key',
            'spec_key_name',
            [
                'attribute' => 'scene_num',
                'headerOptions' => ['style' => 'width:60px'],
                'value' => function($model) {
                    return $model->scene_num;
                }
            ],
            //'amount',
            [
                'attribute' => 'status',
                'headerOptions' => ['style' => 'width:120px'],
                'value' => function($model) {
                    return OrderGoods::$statusNameMap[$model->status];
                }
            ],
            [
                'attribute' => 'init_at',
                'headerOptions' => ['style' => 'width:90px'],
                'value' => function($model) {
                    return $model->init_at == 0 ? null : date('Y-m-d H:i:s', $model->init_at);
                }
            ],
            [
                'attribute' => 'upload_finish_at',
                'headerOptions' => ['style' => 'width:90px'],
                'value' => function($model) {
                    return $model->upload_finish_at == 0 ? null : date('Y-m-d H:i:s', $model->upload_finish_at);
                }
            ],
            [
                'attribute' => 'design_at',
                'headerOptions' => ['style' => 'width:90px'],
                'value' => function($model) {
                    return $model->design_at == 0 ? null : date('Y-m-d H:i:s', $model->design_at);
                }
            ],
            [
                'attribute' => 'print_at',
                'headerOptions' => ['style' => 'width:90px'],
                'value' => function($model) {
                    return $model->print_at == 0 ? null : date('Y-m-d H:i:s', $model->print_at);
                }
            ],
            //'is_del',
            //'created_by',
            //'created_at',
            //'updated_at',
            [
                'label' => Yii::t('app', 'Action'),
                'headerOptions' => ['style' => 'width:100px'],
                'format' => 'raw',
                'value' => function($model) {
                    //订单
                    $view_btn = ResourceHelper::a(I18NUitl::t('app', '{View}'), ['view', 'id' => $model->id], ['class' => 'btn btn-default']);

                    return "$view_btn";
                }
            ],
        ],
    ]);
    ?>
</div>
