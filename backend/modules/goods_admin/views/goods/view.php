<?php

use backend\modules\goods_admin\assets\GoodsModuleAsset;
use common\models\goods\Goods;
use common\modules\rbac\components\ResourceHelper;
use common\utils\I18NUitl;
use yii\helpers\Html;
use yii\web\View;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model Goods */

$this->title = $model->goods_name;
$this->params['breadcrumbs'][] = ['label' => I18NUitl::t('app', '{Goods}{List}'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
GoodsModuleAsset::register($this);
?>
<div class="goods-view">

    <p>
        <?= ResourceHelper::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= ResourceHelper::a(I18NUitl::t('app', '{Material}{Admin}'), ['/goods_admin/material/index', 'goods_id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= ResourceHelper::a(I18NUitl::t('app', '{Scene}{Admin}'), ['/goods_admin/scene/index', 'goods_id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= ResourceHelper::a(I18NUitl::t('app', '{Attribute}{Spec}'), ['/goods_admin/att-spec/index', 'goods_id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <div class="wsk-panel">
        <div class="title">基本信息</div>
        <div class="body">
            <div style="width:50%;display: inline-block;float: left;">
                <?=
                DetailView::widget([
                    'options' => ['class' => 'table table-striped table-bordered wsk-table-detail'],
                    'model' => $model,
                    'attributes' => [
                        'goods_sn',
                        //key:format:label
                        "goodsCategory.name:text:{$model->getAttributeLabel('category_id')}",
                        "goodsModel.name:text:{$model->getAttributeLabel('model_id')}",
                        "owner.nickname:text:{$model->getAttributeLabel('owner_id')}",
                        'goods_name',
                        'goods_cost',
                        'goods_price',
                        'goods_des',
                        'tags',
                        //封面
                        [
                            'attribute' => 'cover_url',
                            'format' => 'raw',
                            'value' => function($model) {
                                return Html::img($model->cover_url, ['style' => 'width:96px']);
                            }
                        ],
                        //视频
                        [
                            'attribute' => 'video_url',
                            'format' => 'raw',
                            'value' => function($model) {
                                return Html::tag('video', '', ['style' => 'width:250px', 'src' => $model->video_url, 'controls' => 'controls']);
                            }
                        ],
                    ],
                ])
                ?>
            </div>
            <div style="width:50%;display: inline-block;float: right;">
                <?=
                DetailView::widget([
                    'options' => ['class' => 'table table-striped table-bordered wsk-table-detail'],
                    'model' => $model,
                    'attributes' => [
                        //状态
                        [
                            'attribute' => 'status',
                            'value' => function($model) {
                                return Goods::$statusKeyMap[$model->status];
                            }
                        ],
                        'store_count',
                        'comment_count',
                        'click_count',
                        'share_count',
                        'like_count',
                        'sale_count',
                        "creater.nickname:text:{$model->getAttributeLabel('created_by')}",
                        "updater.nickname:text:{$model->getAttributeLabel('updated_by')}",
                        'created_at:datetime',
                        'updated_at:datetime',
                    ],
                ])
                ?>
            </div>
        </div>
    </div>
    <div class="wsk-panel">
        <div class="title">商品详情</div>
        <div class="body">
            <div class="goods-content-box">
                <div class="rich-content"><?= $model->goodsDetails->content ?></div>
            </div>
        </div>
    </div>
</div>
