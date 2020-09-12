<?php

use common\models\shop\Shop;
use common\widgets\charts\ChartAsset;
use merchant\assets\SiteAssets;
use yii\web\View;

/** @var $this View */
/** @var $shop Shop */
/** @var $api_url string */

$this->title = Yii::$app->name;

SiteAssets::register($this);
ChartAsset::register($this);


?>
<style>

</style>
<div>
    <div class="site-index">
        <div class="item">
            <label>总绘本数：</label>
            <span class="txt goods_count">0 本</span>
        </div>
        <div class="item">
            <label >总订单数：</label>
            <span class="txt order_count">0 单</span>
        </div>
        <div class="item">
            <label >总收益：</label>
            <span class="txt all_income">0 元</span>
        </div>
        <div class="item">
            <label >本月收益：</label>
            <span class="txt month_income">0 元</span>
        </div>
        <div class="item">
            <label >今天收益：</label>
            <span class="txt day_income">0 元</span>
        </div>
    </div>
</div>

<script>
    window.onload = function(){
        $.get("<?= $api_url ?>/v1/shop/get-shop-stat", {shop_id: <?= $shop->id ?>}, function (r) {
            if(r.code == '0'){
                var data = r.data;
                $('.goods_count').html(data.goods_count+' 本');
                $('.order_count').html(data.order_count+' 单');
                $('.all_income').html(data.all_income+' 元');
                $('.month_income').html(data.month_income+' 元');
                $('.day_income').html(data.day_income+' 元');
            }
        }, 'json');
    }
</script>
