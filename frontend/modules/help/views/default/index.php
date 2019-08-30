<?php

use frontend\modules\help\assets\HelpAsset;

HelpAsset::register($this);
?>
<div class="help-default-index">
    <div class="wsk-panel">
        <div class="wsk-body">
            <?php foreach ($posts as $index => $post): ?>
                <div class="post-item">
                    <a href="/help/default/post?id=<?= $post->id ?>">
                        <?= $index+1 ?>、<?= "$post->title" ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>
