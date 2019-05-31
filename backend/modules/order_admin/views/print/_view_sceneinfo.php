<?php

use common\models\order\WorkflowPrint;
use common\modules\rbac\components\ResourceHelper;
use common\utils\I18NUitl;
use kartik\growl\GrowlAsset;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

GrowlAsset::register($this);

/* @var $model WorkflowPrint */
?>
<div class="wsk-panel goods-info">
    <div class="title">
        场景信息
        <div class="pull-right">
            <?=
            ResourceHelper::a(I18NUitl::t('app', '{Download}{User}{Image}'), ['batch-download-finished-img'], [
                'class' => 'btn btn-primary',
                'onclick' => 'return batchDownload();'
            ]);
            ?>
        </div>
    </div>
    <div class="body">
        <?php
        $form = ActiveForm::begin([
                    'id' => 'search-form',
                    'action' => ['batch-download-finished-img'],
                    'method' => 'post',
        ]);
        ?>
        <?=
        GridView::widget([
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $model->order->orderGoodsScenes,
                'key' => 'id',
            ]),
            // 'filterModel' => new OrderGoodsMaterial(),
            'tableOptions' => ['class' => 'table table-striped table-bordered wsk-table'],
            'layout' => "{items}",
            'columns' => [
                ['class' => 'yii\grid\CheckboxColumn', 'headerOptions' => ['style' => 'width:30px;']],
                [
                    'attribute' => 'name',
                    'headerOptions' => ['style' => 'width:120px;'],
                ],
                [
                    'attribute' => 'effect_url',
                    'headerOptions' => ['style' => 'width:100px;'],
                    'format' => 'raw',
                    'value' => function($model) {
                        return Html::img($model->effect_url, ['style' => 'width:48px;height:48px;']);
                    }
                ],
                [
                    'attribute' => 'demo_url',
                    'headerOptions' => ['style' => 'width:100px;'],
                    'format' => 'raw',
                    'value' => function($model) {
                        return Html::img($model->demo_url, ['style' => 'width:48px;height:48px;']);
                    }
                ],
                [
                    'attribute' => 'user_img_url',
                    'headerOptions' => ['style' => 'width:100px;'],
                    'format' => 'raw',
                    'value' => function($model) {
                        return Html::img($model->user_img_url, ['style' => 'width:48px;height:48px;']);
                    }
                ],
                [
                    'attribute' => 'finish_url',
                    'headerOptions' => ['style' => 'width:100px;'],
                    'format' => 'raw',
                    'value' => function($model) {
                        return Html::img($model->finish_url, ['style' => 'width:48px;height:48px;']);
                    }
                ],
                'des'
            ],
        ]);
        ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<script>
    function batchDownload() {
        $checks = $('.wsk-table tbody input[type=checkbox]:checked');
        if ($checks.length == 0) {
            $.notify({message: '请先选择要下载的文件！'}, {type: 'warning'});
        } else {
            $('#search-form').submit();
        }
        return false;
    }
</script>