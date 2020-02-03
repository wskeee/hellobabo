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
/* @var $worders array */

$this->title = I18NUitl::t('app', 'Order Board');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-goods-index">

    <?php echo $this->render('_search', ['model' => $searchModel ,'worders' => $worders]); ?>
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
            'order_sn:text:'.Yii::t('app','Order Sn'),
            'consignee:text:'.Yii::t('app','Consignee'),
            'goods_name:text:'.Yii::t('app','Goods Name'),
            [
                'label' => Yii::t('app', 'Current Status'),
                'value' => function ($model) {
                    return OrderGoods::$statusNameMap[$model['cur_status']];
                }
            ],
            [
                'label' => Yii::t('app', 'Last Status Time'),
                'format' => 'raw',
                'value' => function ($model) {
                    $status_list = ['print_at','design_at','upload_finish_at','init_at'];
                    $last_time = $model['pay_at'];
                    foreach ($status_list as $status_key){
                        if(!empty($model[$status_key])){
                            $last_time = $model[$status_key];
                            break;
                        }
                    }
                    $relative_time = Yii::$app->formatter->asRelativeTime($last_time);
                    $isOver24Hours = time() - $last_time > 60 * 60 * 24;
                    $relative_time_html = $isOver24Hours ? "<p style='color:#ff3300'>$relative_time</p>" : "<p>$relative_time</p>";
                    $last_time_html = date('Y-m-d H:i:s',$last_time);
                    return "<div>{$relative_time_html}{$last_time_html}</div>";
                }
            ],
            [
                'label' => Yii::t('app', 'Follower Name'),
                'value' => function ($model) use($worders){
                    $worker_list = ['delivery_id','printer_id','designer_id',];
                    $worker_id = null;
                    foreach ($worker_list as $worker_key){
                        if(!empty($model[$worker_key])){
                            $worker_id = $model[$worker_key];
                            break;
                        }
                    }

                    return $worker_id == null ? '无' : $worders[$worker_id];
                }
            ],
            [
                'label' => Yii::t('app', 'Action'),
                'headerOptions' => ['style' => 'width:100px'],
                'format' => 'raw',
                'value' => function ($model) {
                    //订单
                    $view_btn = ResourceHelper::a(I18NUitl::t('app', '{View}'), ['/order_admin/order-goods/view', 'id' => $model['order_goods_id']], ['class' => 'btn btn-default']);

                    return "$view_btn";
                }
            ],
        ],
    ]);
    ?>
</div>
