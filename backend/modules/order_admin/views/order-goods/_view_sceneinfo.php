<?php

use common\models\order\OrderGoods;
use common\models\order\OrderGoodsScenePage;
use common\utils\I18NUitl;
use common\widgets\viewer\ViewerAsset;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

ViewerAsset::register($this);

/* @var $model OrderGoods */
$pages = $model->orderGoodsScenePages;
?>
<div class="wsk-panel goods-info">
    <div class="title"><?= I18NUitl::t('app', '{Scene}{Info}') ?></div>
    <div class="body">
        <?=
        GridView::widget([
            'dataProvider' => new ArrayDataProvider(['allModels' => $pages,]),
            'tableOptions' => ['id' => 'scene-table', 'class' => 'table table-striped table-bordered wsk-table'],
            'layout' => "{items}",
            'columns' => [
                [
                    'attribute' => 'scene.name',
                    'headerOptions' => ['style' => 'width:120px;'],
                ],
                [
                    'attribute' => 'scene.effect_url',
                    'headerOptions' => ['style' => 'width:250px;'],
                    'format' => 'raw',
                    'value' => function($model) {
                        return Html::img($model->scene->effect_url, ['style' => 'max-height:230px;']);
                    }
                ],
                [
                    'attribute' => 'effect_url',
                    'headerOptions' => ['style' => 'width:100px;'],
                    'format' => 'raw',
                    'value' => function($model) {
                        return Html::img($model->effect_url, ['style' => 'width:100%;']);
                    }
                ],
                [
                    'attribute' => 'user_img_url',
                    'headerOptions' => ['style' => 'width:100px;'],
                    'format' => 'raw',
                    'value' => function($model) {
                        /* @var $model OrderGoodsScenePage */
                        
                        return $model->is_required ? Html::img($model->user_img_url, ['style' => 'width:100%;']) : '无需用户上传相片';
                    }
                ],
                [
                    'attribute' => 'finish_url',
                    'headerOptions' => ['style' => 'width:100px;'],
                    'format' => 'raw',
                    'value' => function($model) {
                        return Html::img($model->finish_url, ['style' => 'width:100%;']);
                    }
                ],
                'des'
            ],
        ]);
        ?>
    </div>
</div>

<script>
    window.addOnReady(function () {
        initViewer();
        mergeTable();
    });

    function initViewer() {
        var $image = $('#image');

        $image.viewer({
            inline: false,
            viewed: function () {
                $image.viewer('zoomTo', 1);
            }
        });

        // Get the Viewer.js instance after initialized
        var viewer = $image.data('viewer');

        // View a list of images
        $('#scene-table').viewer();
    }

    function mergeTable() {
        //合并相同列
        var len = <?= count($pages) ?>;
        var $table = $('#scene-table');
        autoRowSpan($table[0], 0, 1);
        autoRowSpan($table[0], 0, 0);
    }
    /**
     * 自动合并table行列
     * @param {Table} tb 
     * @param {int} row     行,开始行
     * @param {int} col     列
     * @returns {void}     
     **/
    function autoRowSpan(tb, row, col) {
        var lastValue = "";
        var value = "";
        var pos = 1;
        for (var i = row; i < tb.rows.length; i++) {
            value = tb.rows[i].cells[col].outerHTML;
            if (lastValue == value) {
                tb.rows[i].deleteCell(col);
                tb.rows[i - pos].cells[col].rowSpan = tb.rows[i - pos].cells[col].rowSpan + 1;
                pos++;
            } else {
                lastValue = value;
                pos = 1;
            }
        }
    }
</script>