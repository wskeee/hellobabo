<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\platform\Agency */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Agencies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="agency-view">

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'des',
            [
                'attribute' => 'cover_url',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::img($model->cover_url, ['style' => 'width:120px;']);
                }
            ],
            'levelName',
            'regions',
            'address',
            'admin.nickname:text:'.$model->getAttributeLabel('admin_id'),
            'contacts_name',
            'contacts_phone',
            //'idcard',
            //'idcard_img_url:url',
            //'province',
            //'city',
            //'district',
            //'town',
            //'status',
            //'apply_time:datetime',
            //'check_id',
            //'check_time:datetime',
            //'check_feedback',
            //'is_del',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
