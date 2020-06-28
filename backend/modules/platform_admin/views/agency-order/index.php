<?php

use common\modules\rbac\components\ResourceHelper;
use common\utils\I18NUitl;
use common\widgets\btnloader\BtnLoaderAsset;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\platform\searchs\AgencyOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $agencys array */

BtnLoaderAsset::register($this);

$this->title = I18NUitl::t('app', '{Agency}{Order}');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agency-servicer-index">

    <div class="pull-right" style="display: inline-block;margin-bottom: 10px;">
        <?= $this->render('_search', ['model' => $searchModel, 'agencys' => $agencys]); ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped table-bordered wsk-table'],
        'layout' => "{items}\n{summary}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 所属代理
            'agency.name:text:' . $searchModel->getAttributeLabel('agency_id'),
            'order.order_sn',
            'order.consignee:text:' . $searchModel->getAttributeLabel('user_name'),
            'order.phone:text:' . $searchModel->getAttributeLabel('user_phone'),
            'order.created_at:datetime',
            [
                'attribute' => 'is_self',
                'value' => function ($model) {
                    return $model->is_self ? '是' : '否';
                }
            ],
        ],
    ]); ?>
</div>