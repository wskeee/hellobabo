<?php

use common\models\activity\VoteActivity;
use common\utils\I18NUitl;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\web\YiiAsset;

/* @var $this View */
/* @var $model VoteActivity */

YiiAsset::register($this);

$this->title = $model->name;
$this->params['breadcrumbs'][] = I18NUitl::t('app', '{Vote}{Info}');
?>

<style>
    .rank-box{
        display: flex;
        margin-left: 20px;
    }

    .rank-item{
        border: solid 1px #e4e4e4;
        margin-right: 10px;
        padding: 5px;
    }
    
    .week-box{
        margin-left: 20px;
    }
</style>

<div class="vote-activity-view">
    <div class="wsk-panel">
        <div class="title">活动总数据</div>
        <div class="body">
            <h5>参选总人数：<?= $all_info['max_hand'] ?> 人</h5>
            <h5>前5名：</h5>
            <?=
            GridView::widget([
                'dataProvider' => new ArrayDataProvider([
                    'allModels' => $all_info['rank']
                        ]),
                'tableOptions' => ['class' => 'table table-striped table-bordered wsk-table'],
                'layout' => "{items}",
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'headerOptions' => ['style' => 'width:40px;'],
                    ],
                    [
                        'attribute' => 'target_name',
                        'label' => Yii::t('app', 'Name'),
                        'headerOptions' => ['style' => 'width:120px;'],
                    ],
                    [
                        'attribute' => 'target_age',
                        'label' => Yii::t('app', 'Age'),
                        'headerOptions' => ['style' => 'width:60px;'],
                    ],
                    [
                        'attribute' => 'target_img',
                        'label' => Yii::t('app', 'Img'),
                        'format' => 'raw',
                        'headerOptions' => ['style' => 'width:64px;'],
                        'value' => function($model) {
                            return Html::img($model['target_img'], ['style' => 'width:100%;']);
                        }
                    ],
                    'score:text:票数'
                ],
            ]);
            ?>
        </div>
    </div>

    <?php foreach ($month_info as $month): ?>
        <div class="wsk-panel">
            <div class="title"><?= $month['name'] ?> 月数据</div>
            <div class="body">
                <h5>月前5名：</h5>
                <div class="rank-box">
                    <?php foreach ($month['rank'] as $index => $hand): ?>
                        <div class="rank-item">
                            <span><?= $index+1 ?>、</span>
                            <img src="<?= $hand['target_img'] ?>" style="width:32px;height:32px;" />
                            <span><?= $hand['target_name'] ?></span>
                            <span>（<?= $hand['score'] ?>）</span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <h5>周：</h5>
                <?php foreach ($month['weeks'] as $index => $week): ?>
                    <div class="week-box">
                        <p>第 <?= $index + 1 ?> 周：</p>
                        <div class="rank-box">
                            <?php if(count($week['rank']) == 0): ?>
                                无
                            <?php endif; ?>
                            <?php foreach ($week['rank'] as $index => $hand): ?>
                                <div class="rank-item">
                                    <span><?= $index+1 ?>、</span>
                                    <img src="<?= $hand['target_img'] ?>" style="width:32px;height:32px;" />
                                    <span><?= $hand['target_name'] ?></span>
                                    <span>（<?= $hand['score'] ?>）</span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
