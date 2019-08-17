<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\goods\GoodsElement */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Goods Elements'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="goods-element-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ])
        ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'type',
            [
                'attribute' => 'thumb_url',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::img($model->thumb_url, ['style' => 'width:64px;height:64px']);
                }
            ],
            [
                'attribute' => 'img_url',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::img($model->img_url, ['style' => 'width:196px;height:196px']);
                }
            ],
            [
                'attribute' => 'sound_url',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::tag('audio', '', ['src' => $model->sound_url, 'controls' => 'controls', 'style' => 'width:100%;height:64px']);
                }
            ],
            'config:ntext',
            'des',
        ],
    ])
    ?>

</div>
