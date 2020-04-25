<?php

use common\utils\I18NUitl;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\order\Coupon */

$this->title = I18NUitl::t('app', '{Create} {Coupon}');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Coupons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
