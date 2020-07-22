<?php

use common\models\order\OrderGoodsScenePage;
use common\models\order\WorkflowDesign;
use common\modules\rbac\components\ResourceHelper;
use common\utils\I18NUitl;
use common\widgets\viewer\ViewerAsset;
use common\widgets\webuploader\FilePicker;
use kartik\growl\GrowlAsset;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

GrowlAsset::register($this);
ViewerAsset::register($this);

/* @var $model WorkflowDesign */
$pages = $model->orderGoods->orderGoodsScenePages;
$canEdit = $model->status == WorkflowDesign::STATUS_RUNGING || $model->status == WorkflowDesign::STATUS_CHECK_FAIL;
?>
<div class="wsk-panel goods-info">
    <div class="title">
        场景信息
        <div class="pull-right">
            <?=
            ResourceHelper::a(I18NUitl::t('app', '{Download}{User}{Image}'), ['batch-download-user-img'], [
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
                ['class' => 'yii\grid\CheckboxColumn', 'headerOptions' => ['style' => 'width:30px;']],
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
                    'label' => 'Pose图',
                    'headerOptions' => ['style' => 'width:250px;'],
                    'format' => 'raw',
                    'value' => function($model) {
                        /** @var $model OrderGoodsScenePage */
                        $pose = $model->sourcePage->pose;
                        return $pose ? Html::img($model->sourcePage->pose->filepath, ['style' => 'width:100%;']) : '';
                    }
                ],
                [
                    'attribute' => 'user_img_url',
                    'headerOptions' => ['style' => 'width:250px;'],
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
                    'value' => function($model)use($canEdit) {
                        $picker = FilePicker::widget([
                                    'name' => $model->id,
                                    'value' => $model->finish_url,
                                    'pluginOptions' => [
                                        'formData' => [
                                            'is_adobe' => 1,
                                        ],
                                        'accept' => [
                                            'extensions' => 'zip,jpg,jpeg,',
                                            'mimeTypes' => 'application/zip,image/jpg,image/jpeg',
                                        ]
                                    ],
                                    'pluginEvents' => [
                                        "fileDequeued" => "function(evt,file){onProductClear($model->id)}",
                                        'uploadComplete' => "function(evt,dbFile,file){onProductUploadSuccess($model->id,dbFile)}",
                                    ]
                        ]);
                        if($model->is_hide){
                            return '<span style="color:#28b28b">隐藏页，无需上传</span>';
                        }else{
                            return $canEdit ? $picker : ($model->finish_url == "" ? '<span style="color:#ff3300">未上传</span>' : '<span style="color:#28b28b">已上传</span>');
                        }
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
     * 成品上传完成
     * 
     * @param {type} evt
     * @param {type} dbFile
     * @returns {void}
     */
    function onProductUploadSuccess(pid, dbFile) {
        var skin_url = dbFile ? dbFile.url : "";
        var adobe_id = (dbFile && dbFile.metadata.adobe_id) ? dbFile.metadata.adobe_id : "";
        $.post('save-product', {pid: pid, skin_url: skin_url, adobe_id: adobe_id});
    }

    /**
     * 清除成品
     * 
     * @param {type} pid
     * @returns {undefined}
     */
    function onProductClear(pid) {
        $.post('save-product', {pid: pid, skin_url: ""});
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