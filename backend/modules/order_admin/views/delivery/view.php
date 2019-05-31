<?php

use backend\modules\order_admin\assets\OrderModuleAsset;
use common\models\order\WorkflowDelivery;
use common\modules\rbac\components\ResourceHelper;
use common\utils\I18NUitl;
use yii\web\View;
use yii\web\YiiAsset;

OrderModuleAsset::register($this);
YiiAsset::register($this);

/* @var $this View */
/* @var $model WorkflowDelivery */

$this->title = I18NUitl::t('app', '{Delivery}{Detail}:') . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Deliveries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="workflow-delivery-view">

    <p>
        <?php
        if ($model->status == WorkflowDelivery::STATUS_WAIT_START) {
            //开始
            echo ResourceHelper::a(I18NUitl::t('app', '{Delivery}'), ['shipping', 'id' => $model->id], [
                'class' => 'btn btn-primary',
            ]);
        } else {
            //结束
            echo ResourceHelper::a(I18NUitl::t('app', 'Update'), ['update', 'id' => $model->id], [
                'class' => 'btn btn-primary',
            ]);
        }
        ?>
    </p>

    <!-- 基本信息 -->
    <?= $this->render('_view_baseinfo', ['model' => $model]) ?>

</div>
