<?php

use backend\modules\order_admin\assets\OrderModuleAsset;
use common\models\order\Order;
use common\utils\I18NUitl;
use yii\web\View;
use yii\web\YiiAsset;

OrderModuleAsset::register($this);
YiiAsset::register($this);

/* @var $this View */
/* @var $model Order */

$this->title = I18NUitl::t('app', '{Order}{Detail}：') . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view">

    <!-- 基本信息 -->
    <?= $this->render('_view_baseinfo', ['model' => $model]) ?>
    <!-- 初始信息 -->
    <?= $this->render('_view_initinfo', ['model' => $model]) ?>
    <!-- 场景信息 -->
    <?= $this->render('_view_sceneinfo', ['model' => $model]) ?>
    <!-- 操作记录 -->
    <?= $this->render('_view_actionlog', ['model' => $model]) ?>
    
</div>
