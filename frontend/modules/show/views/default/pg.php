<?php

use common\widgets\babobook\BabobookAsset;
use yii\web\View;

/* @var $this View */
$sourcePath = $this->assetManager->getPublishedUrl(BabobookAsset::register($this)->sourcePath);
$app_data ['common']['path'] = "{$sourcePath}/common/";
foreach($app_data['scenes'] as $scene){
    $this->registerJsFile("{$scene['path']}skin.js");
}
?>
<div class="show-default-pg">
    <div id="animation_container" style="background-color:rgba(255, 255, 255, 1.00); ">
        <canvas id="canvas"
                style="position: absolute; display: block; background-color:rgba(255, 255, 255, 1.00);"></canvas>
        <div id="dom_overlay_container"
             style="pointer-events:none; overflow:hidden; position: absolute; left: 0px; top: 0px; display: block;">
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
