<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\order\CouponSwap */

$this->title = Yii::t('app', 'Create Coupon Swap');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Coupon Swaps'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-swap-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
