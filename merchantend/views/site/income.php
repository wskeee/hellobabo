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

$type = Yii::$app->request->getQueryParam('type',1);

?>
<style>

</style>
<div>
    <div class="site-income">
        <div class="tab-head">
            <a class="item current" data-type="1">统计</a>
            <a class="item" data-type="2">明细</a>
        </div>
        <?= $type == 1 ? $this->render('_income_stat') :  $this->render('_income_list')?>
    </div>
</div>

<?php

$js = <<<JS
    var navItems = $('.site-income .tab-head .item');
    var currentType = "$type";
    // 删除当前已选择
    navItems.removeClass('current');
    // 更新当前已选择
    $.each(navItems, function(i,item){
        var type = $(item).attr('data-type');
        $(item).attr('href','/site/income?type='+type);
        if(currentType === type){
            $(item).addClass('current');
        }
    });
JS;
$this->registerJs($js, View::POS_READY);
?>
