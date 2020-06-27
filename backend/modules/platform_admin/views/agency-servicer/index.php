<?php

use common\modules\rbac\components\ResourceHelper;
use common\utils\I18NUitl;
use common\widgets\btnloader\BtnLoaderAsset;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\platform\searchs\AgencyServicerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

BtnLoaderAsset::register($this);

$this->title = Yii::t('app', 'Servicers');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Agencies'), 'url' => ['/platform_admin/agency/index']];
$this->params['breadcrumbs'][] = ['label' => $searchModel->agency->name, 'url' => ['/platform_admin/agency/view', 'id' => $searchModel->agency_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="agency-servicer-index">

        <div class="alert alert-danger" role="alert">
            客服绑定：<br/>
            1、客服人员登录小程序并授权<br/>
            2、后台关联服务人员即可<br/>
            3、绑定后，该客服所下的订单都算入代理商自身订单<br/>
        </div>

        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <p class="btn-box">
            <?=
            Html::button(Yii::t('app', 'Bind'), [
                'class' => 'btn btn-success',
                'data-toggle' => "modal",
                'data-target' => "#bind-modal"
            ]);
            ?>
        </p>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'user.nickname:text:客服名称',
                [
                    'attribute' => 'user.avatar',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::img($model->user->avatar, ['style' => 'width:64px;']);
                    }
                ],
                //'created_at',
                //'updated_at',

                [
                    'class' => 'yii\grid\ActionColumn',
                    'buttons' => [
                        'delete' => function($url, $model) {
                            return Html::a(Yii::t('app', 'Delete'),
                                ['delete', 'id' => $model->id],
                                ['class' => 'btn btn-danger','data-method' => 'post']);
                        },
                    ],
                    'headerOptions' => ['style' => 'width:80px'],
                    'template' => '{delete}',
                ],
            ],
        ]); ?>
    </div>

<?= $this->render('____modal_bind', ['url' => Url::to(['bind','agency_id' => $searchModel->agency_id])]) ?>