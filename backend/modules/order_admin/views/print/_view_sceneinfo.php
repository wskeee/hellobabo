<?php

use common\models\order\OrderGoodsScenePage;
use common\models\order\WorkflowPrint;
use common\widgets\viewer\ViewerAsset;
use kartik\growl\GrowlAsset;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

GrowlAsset::register($this);
ViewerAsset::register($this);

/* @var $model WorkflowPrint */
$pages = $model->orderGoods->orderGoodsScenePages;
?>
<div class="wsk-panel goods-info">
    <div class="title">
        场景信息
    </div>
    <div class="body">
        <?php
        $form = ActiveForm::begin([
                    'id' => 'search-form',
                    'action' => ['batch-download-user-img'],
                    'method' => 'post',
        ]);
        ?>
        <?=
        GridView::widget([
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $pages,
                'key' => 'id',
                    ]),
            // 'filterModel' => new OrderGoodsMaterial(),
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
                        return $model->finish_url == "" ? '<span style="color:#ff3300">未上传</span>' : '<span style="color:#28b28b">已上传</span>';
                    }
                ],
                'des',
                [
                    'headerOptions' => ['style' => 'width:100px;'],
                    'format' => 'raw',
                    'value' => function($model) {
                        return Html::button('预览', ['class' => 'btn btn-default', 'data-pid' => $model->id, 'onClick' => 'onPreview($(this))']);
                    }
                ],
            ],
        ]);
        ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<script>
    window.addOnReady(function () {
        initViewer();
        mergeTable();
    });
    /**
     * 图片预览
     * 
     * @returns {void}
     */
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

    /**
     * 表行合并
     * 
     * @returns {void}
     */
    function mergeTable() {
        //合并相同列
        var $table = $('#scene-table');
        autoRowSpan($table[0], 0, 2);
        autoRowSpan($table[0], 0, 1);
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

    /**
     * 批量下载
     * @returns {Boolean}
     */
    function batchDownload() {
        $checks = $('.wsk-table tbody input[type=checkbox]:checked');
        if ($checks.length == 0) {
            $.notify({message: '请先选择要下载的文件！'}, {type: 'warning'});
        } else {
            $('#search-form').submit();
        }
        return false;
    }

    /**
     * 预览
     * 
     * @param {type} $dom
     * @returns {undefined}
     */
    function onPreview($dom) {
        const pid = $dom.attr('data-pid')
        var url = '<?= Yii::$app->params['hellobabo']['preview_url'] ?>';
        window.open(url + '?page_ids=' + pid);
    }

</script>