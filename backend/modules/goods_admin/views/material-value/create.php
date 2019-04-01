<?php

use common\models\goods\GoodsMaterialValue;
use yii\web\View;

/* @var $this View */
/* @var $model GoodsMaterialValue */

?>
<div class="goods-material-value-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
