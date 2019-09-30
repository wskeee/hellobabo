<?php

use common\models\activity\VoteActivity;
use common\utils\I18NUitl;
use yii\helpers\Html;
use yii\web\View;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model VoteActivity */

YiiAsset::register($this);

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vote Activities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vote-activity-view">
    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(I18NUitl::t('app', '{Vote Activity Hand}{Check}'), ['/platform_admin/vote-activity-hand-apply/index', 'activity_id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(I18NUitl::t('app', '{Activity}{Statistics}'), ['/platform_admin/vote-activity/info', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            [
                'attribute' => 'cover_url',
                'format' => 'raw',
                'value' => Html::img($model->cover_url, ['style' => 'width:120px;'])
            ],
            [
                'attribute' => 'share_poster_url',
                'format' => 'raw',
                'value' => Html::img($model->share_poster_url, ['style' => 'width:120px;'])
            ],
            'start_time',
            'end_time',
            'is_publish',
            [
                'attribute' => 'content',
                'format' => 'raw',
                'value' => $model->content
            ],
        ],
    ])
    ?>

</div>
