<?php

use common\models\platform\searchs\PostSearch;
use common\utils\I18NUitl;
use common\widgets\grid\GridViewChangeSelfColumn;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel PostSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', 'Posts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

    <p>
        <?= Html::a(I18NUitl::t('app', '{Create}{Post}'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            "type.name:text:{$searchModel->getAttributeLabel('type_id')}",
            'title',
            [
                'attribute' => 'is_publish',
                'class' => GridViewChangeSelfColumn::class
            ],
            //'created_at',
            //'updated_at',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>
</div>
