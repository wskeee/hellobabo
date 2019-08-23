<?php

use common\models\order\WorkflowDesign;
use common\widgets\webuploader\ImagePicker;
use yii\helpers\Html;

/* @var $model WorkflowDesign */
?>
<div class="wsk-panel goods-info">
    <div class="title">
        封面信息
    </div>
    <div class="body">
        <?php
        $picker = ImagePicker::widget([
                    'name' => 'cover_url',
                    'value' => $model->orderGoods->user_cover_url,
                    'pluginEvents' => [
                        "fileDequeued" => "onUserCoverClear",
                        'uploadComplete' => "onUserCoverUploadComplete",
                    ]
        ]);
        $image = Html::img($model->orderGoods->user_cover_url, ['style' => 'width:128px;']);
        // 编辑状态 与 完成状态
        echo ($model->status == WorkflowDesign::STATUS_RUNGING || $model->status == WorkflowDesign::STATUS_CHECK_FAIL) ? $picker : $image;
        ?>
    </div>
</div>
<script>
    function onUserCoverClear(evt, file) {
        $.post('save-user-cover', {ogid:<?= $model->orderGoods->id ?>, cover_url: ''});
    }
    function onUserCoverUploadComplete(evt, dbFile, file) {
        $.post('save-user-cover', {ogid:<?= $model->orderGoods->id ?>, cover_url: dbFile.url});
    }
</script>