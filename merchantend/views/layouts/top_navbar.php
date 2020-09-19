<?php

use common\models\shop\Shop;
use yii\web\View;

$shop = Shop::findOne(['user_id' => Yii::$app->user->id]);
?>

<div class="nav">
    <div class="content">
        <div style="display: flex;">
            <img class="navbar-brand" src="<?= $shop->logo_url ?>">
            <div class="navbar-info">
                <div style="font-size:16px;"><?= $shop->name ?>，战略合作伙伴</div>
                <div style="font-size:9px;">欢迎使用芭宝收益平台</div>
            </div>
        </div>
        <div class="logout-box">
            <a href="/site/logout" data-method="post"><i class="glyphicon glyphicon-off"></i><br/>退出登录</a>
        </div>
    </div>
</div>

<?php
$js = <<<JS
   

JS;
$this->registerJs($js, View::POS_READY);
?>
