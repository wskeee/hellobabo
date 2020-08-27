<?php

use common\models\order\WorkflowDesign;
use common\widgets\webuploader\ImagePicker;
use yii\helpers\Html;

/* @var $model WorkflowDesign */
?>
<style>
    .user-info{
        display: flex;
    }
    .goods-info .item{
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-right: 20px;
    }
</style>
<div class="wsk-panel goods-info">
    <div class="title">
        用户信息【必传】
    </div>
    <div class="body user-info">
        <div class="item">
            <label>用户封面</label>
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
        <div class="item">
            <label>分享缩略图</label>
            <?php
            $picker = ImagePicker::widget([
                'name' => 'share_thumb_url',
                'value' => $model->orderGoods->share_thumb_url,
                'pluginEvents' => [
                    "fileDequeued" => "onShareThumbClear",
                    'uploadComplete' => "onShareThumbUploadComplete",
                ]
            ]);
            $image = Html::img($model->orderGoods->share_thumb_url, ['style' => 'width:128px;']);
            // 编辑状态 与 完成状态
            echo ($model->status == WorkflowDesign::STATUS_RUNGING || $model->status == WorkflowDesign::STATUS_CHECK_FAIL) ? $picker : $image;
            ?>
        </div>
        <div class="item">
            <label>分享海报</label>
            <?php
            $picker = ImagePicker::widget([
                'name' => 'user_poster_url',
                'value' => $model->orderGoods->user_poster_url,
                'pluginEvents' => [
                    "fileDequeued" => "onPosterClear",
                    'uploadComplete' => "onPosterUploadComplete",
                ]
            ]);
            $image = Html::img($model->orderGoods->user_poster_url, ['style' => 'width:128px;']);
            // 编辑状态 与 完成状态
            echo ($model->status == WorkflowDesign::STATUS_RUNGING || $model->status == WorkflowDesign::STATUS_CHECK_FAIL) ? $picker : $image;
            ?>
        </div>
    </div>
</div>
<script>
    /* 封面 */
    function onUserCoverClear(evt, file) {
        $.post('save-user-cover', {ogid:<?= $model->orderGoods->id ?>, cover_url: ''});
    }
    function onUserCoverUploadComplete(evt, dbFile, file) {
        $.post('save-user-cover', {ogid:<?= $model->orderGoods->id ?>, cover_url: dbFile.url});
    }
    /* 缩略图 */
    function onShareThumbClear(evt, file) {
        $.post('save-share-thumb', {ogid:<?= $model->orderGoods->id ?>, share_thumb_url: ''});
    }
    function onShareThumbUploadComplete(evt, dbFile, file) {
        $.post('save-share-thumb', {ogid:<?= $model->orderGoods->id ?>, share_thumb_url: dbFile.url});
    }
    /* 分享海报 */
    function onPosterClear(evt, file) {
        $.post('save-poster', {ogid:<?= $model->orderGoods->id ?>, poster_url: ''});
    }
    function onPosterUploadComplete(evt, dbFile, file) {
        $.post('save-poster', {ogid:<?= $model->orderGoods->id ?>, poster_url: dbFile.url});
    }
</script>