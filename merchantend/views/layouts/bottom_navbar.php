<?php

use yii\web\View;

$route = Yii::$app->controller->getRoute();
?>

<div class="bottom-nav">
    <a class="item" data-url="site/index">首页</a>
    <a class="item" data-url="site/income">收益</a>
    <a class="item" data-url="site/rank">排行</a>
</div>

<?php


$js = <<<JS
    var navItems = $('.bottom-nav .item');
    var route = "$route";
    // 删除当前已选择
    navItems.removeClass('current');
    // 更新当前已选择
    $.each(navItems, function(i,item){
        var url = $(item).attr('data-url');
        $(item).attr('href','/'+url);
        console.log(route,url);
        if(route === url){
            $(item).addClass('current');
        }
    });
JS;
$this->registerJs($js, View::POS_READY);
?>
