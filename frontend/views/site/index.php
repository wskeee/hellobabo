<?php

use common\widgets\charts\ChartAsset;
use frontend\assets\SiteAssets;
use yii\web\View;

/* @var $this View */

$this->title = Yii::$app->name;

SiteAssets::register($this);
ChartAsset::register($this);

?>
<style>
    .huiben {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .huiben img {
        width:400px;
    }
</style>
<div class="site-index">
    <h2 style="text-align: center;"><?= Yii::$app->name ?></h2>
    <!-- 内容 -->
    <div class="container huiben">
        <img src="imgs/site/huiben1.jpg">
        <img src="imgs/site/huiben2.jpg">
    </div>
</div>
