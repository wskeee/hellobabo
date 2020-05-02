<?php

use common\widgets\babobook\BabobookAsset;
use yii\web\View;

/* @var $this View */
$sourcePath = $this->assetManager->getPublishedUrl(BabobookAsset::register($this)->sourcePath);
$app_data ['common']['path'] = "{$sourcePath}/common/";
foreach ($app_data['scenes'] as $scene) {
    $this->registerJsFile("{$scene['path']}skin.js");
}
?>
<style>
    .wrap-empty{
        min-height: unset;
        height: 100%;
    }
    .show-default-pg{
        height:100%;
    }
</style>
<div class="show-default-pg">
    <div id="animation_container" class="animation_container" style="background-color:rgba(255, 255, 255, 1.00); ">
        <div class="book-box">
            <canvas id="book_canvas"></canvas>
            <div id="dom_overlay_container"
                 style="pointer-events:none; overflow:hidden; position: absolute; left: 0px; top: 0px; display: block;">
            </div>
            <div class="page-num-box">
                <span class="txt page-num"></span>
            </div>
            <div class="head-top">
                <img class="top-left" src="<?= $sourcePath ?>/images/radius-top-left.png"/>
                <img class="top-right" src="<?= $sourcePath ?>/images/radius-top-right.png"/>
            </div>
            <div class="head-bottom">
                <img class="bottom-left" src="<?= $sourcePath ?>/images/radius-bottom-left.png"/>
                <img class="bottom-right" src="<?= $sourcePath ?>/images/radius-bottom-right.png"/>
            </div>
        </div>
    </div>
    <div class="float-view" style="display: none">
        <div class="share-btn">
            <img src="<?= $sourcePath ?>/images/share.png">
            <span class="txt share-txt">赚￥</span>
        </div>
        <div class="buy-btn">
            <span class="txt buy-txt">定制</span>
            <img src="<?= $sourcePath ?>/images/buy.png">
        </div>
    </div>
    <div class="preloader-box">
        <img id="preloader" src="<?= $sourcePath ?>/images/_preloader.gif">
        <div id="preloader-txt"></div>
    </div>
</div>
<script>
    window.appData = <?= json_encode($app_data) ?>
</script>
