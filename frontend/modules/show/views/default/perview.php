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
<div class="show-default-pg">
    <div id="animation_container" style="background-color:rgba(255, 255, 255, 1.00); ">
        <canvas id="book_canvas"
                style="position: absolute; display: block; background-color:rgba(255, 255, 255, 1.00);"></canvas>
        <div id="dom_overlay_container"
             style="pointer-events:none; overflow:hidden; position: absolute; left: 0px; top: 0px; display: block;">
        </div>
    </div>
    <div class="float-view" style="display: none">
        <div class="share-btn">
            <img src="<?= $sourcePath ?>/images/share.png">
            <span class="txt share-txt">赚￥</span>
        </div>
        <div class="page-num-box">
            <span class="txt page-num">1 / 1</span>
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
