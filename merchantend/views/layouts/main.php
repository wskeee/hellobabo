<?php

use common\widgets\Alert;
use merchant\assets\AppAsset;
use kartik\widgets\AlertBlock;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;

/* @var $this View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?= $this->render('top_navbar') ?>

    <div class="wrap-content">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= AlertBlock::widget([
            'useSessionFlash' => TRUE,
            'type' => AlertBlock::TYPE_GROWL,
            'delay' => 0
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
        <?= $this->render('model') ?>
    </div>

    <?= $this->render('bottom_navbar') ?>
</div>

<!--<footer class="footer">
    <div class="copy-right-box">
        <div class="container copy-right-content">
            <p>Copyright © <?/*= date('Y') */?> <?/*= Html::encode('时代互娱（广州）科技有限公司') */?> 版权所有 <a href="http://www.beian.miit.gov.cn">粤ICP备20012458号</a></p>
        </div>
    </div>
</footer>-->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
