<?php

use common\models\platform\Post;
use frontend\modules\help\assets\HelpAsset;

HelpAsset::register($this);

/* @var $post Post */
?>
<div class="help-default-post">
    <!-- 不加这个顶部会有空白 -->
    <!--<div style="height: 1px"></div>-->
    <div class="post-content">
        <div class="p-title" style="display: none;"><?= $post->title ?></div>
        <div class="p-content">
            <?= $post->content ?>
        </div>
    </div>
</div>
