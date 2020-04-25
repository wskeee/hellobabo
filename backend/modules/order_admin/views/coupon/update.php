<?php

use common\utils\I18NUitl;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\order\Coupon */

$this->title = I18NUitl::t('app', '{Update} {Coupon}: {name}', [
    'name' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Coupons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="coupon-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
