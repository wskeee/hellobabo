<?php

use common\models\shop\Shop;
use yii\web\View;

$shop = Shop::findOne(['user_id' => Yii::$app->user->id]);
?>

<div class="nav">
    <img class="navbar-brand" src="<?= $shop->logo_url ?>">
    <div style="display: flex;flex-direction: column;justify-content: center;">
        <div><?= $shop->name ?></div>
        <div style="color: #aaa;">产品合作方</div>
        <a style="position: absolute;right: 10px;" href="/site/logout" data-method="post">登出<i class="glyphicon glyphicon-log-out"></i></a>
    </div>
</div>

<?php
$js = <<<JS
   

JS;
$this->registerJs($js, View::POS_READY);
?>
