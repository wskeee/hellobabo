<?php

use common\widgets\grid\GridViewChangeSelfColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\platform\searchs\CommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Comments');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-index">

    <div class="pull-right btn-box" style="margin-bottom: 10px;">
        <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped table-bordered wsk-table'],
        'layout' => "{items}\n{summary}\n{pager}",
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'title',
            'content',
            //'depth',
            //'thread:ntext',
            'creator.nickname',
            //'is_del',
            [
                'attribute' => 'is_hide',
                'value' => function ($model) {
                    return $model->is_hide ? '匿名' : '';
                }
            ],
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return date('Y-m-d H:i:s', $model->created_at);
                }
            ],
            [
                'class' => GridViewChangeSelfColumn::class,
                'attribute' => 'is_del',
            ],
            //'updated_at',
        ],
    ]); ?>
</div>
