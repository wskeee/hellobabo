<?php

use common\utils\I18NUitl;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\platform\searchs\AgencySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Agencies');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agency-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(I18NUitl::t('app', '{Create}{Agency}'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'levelName',
            'regions',
            'admin.nickname:text:' . $searchModel->getAttributeLabel('admin_id'),
            'contacts_name',
            'contacts_phone',
            'order_num_all',
            'order_num_self',
            //'idcard',
            //'idcard_img_url:url',
            //'province',
            //'city',
            //'district',
            //'town',
            //'address',
            //'status',
            //'apply_time:datetime',
            //'check_id',
            //'check_time:datetime',
            //'check_feedback',
            //'is_del',
            //'created_at',
            //'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'View'), ['view', 'id' => $model->id], ['class' => 'btn btn-default']);
                    },
                    'servicer' => function ($url, $model) {
                        return Html::a(I18NUitl::t('app', '{Servicer}{Admin}'), ['/platform_admin/agency-servicer/index', 'agency_id' => $model->id], ['class' => 'btn btn-default']);
                    },
                ],
                'headerOptions' => ['style' => 'width:160px'],
                'template' => '{view} {servicer}',
            ],
        ],
    ]); ?>
</div>
