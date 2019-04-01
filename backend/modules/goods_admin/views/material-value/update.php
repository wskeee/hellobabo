<?php

use common\models\goods\GoodsMaterialValue;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model GoodsMaterialValue */

?>
<div class="goods-material-value-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
