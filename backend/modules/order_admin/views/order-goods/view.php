<?php

use common\models\order\OrderGoods;
use common\modules\rbac\components\ResourceHelper;
use common\utils\I18NUitl;
use kartik\growl\GrowlAsset;
use yii\helpers\Html;
use yii\web\View;
use yii\web\YiiAsset;

/* @var $this View */
/* @var $model OrderGoods */

$this->title = $model->goods_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Order'), 'url' => ['/order_admin/default/view', 'id' => $model->order_id]];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
GrowlAsset::register($this);
?>
<script>
    var callbacks = [];
    window.addOnReady = function (cb) {
        callbacks.push(cb);
    }
    window.onload = function () {
        $('.check-btn').on('click', function (e) {
            showModal($(this).attr('href'));
            return false;
        });

        for (var i = 0, len = callbacks.length; i < len; i++) {
            callbacks[i]();
        }
    }
</script>

<div class="order-goods-view">

    <p>
        <?php
        if ($model->status == OrderGoods::STATUS_UPLOAD_PIC_CHECK) {
            echo ResourceHelper::a(I18NUitl::t('app', '{Up Img}{Check}'), ['/order_admin/order-goods/upimg-check', 'id' => $model->id], [
                'class' => 'btn btn-primary check-btn',
                'data-toggle' => "modal",
                'data-target' => "#check-modal"
            ]);
        } else if ($model->status == OrderGoods::STATUS_DESIGN_CHECK) {
            echo ResourceHelper::a(I18NUitl::t('app', '{Design}{Check}'), ['/order_admin/order-goods/design-check', 'id' => $model->id], [
                'class' => 'btn btn-primary check-btn',
                'data-toggle' => "modal",
                'data-target' => "#check-modal"
            ]);
        }else if ($model->status == OrderGoods::STATUS_PRINT_CHECK) {
            echo ResourceHelper::a(I18NUitl::t('app', '{Print}{Check}'), ['/order_admin/order-goods/print-check', 'id' => $model->id], [
                'class' => 'btn btn-primary check-btn',
                'data-toggle' => "modal",
                'data-target' => "#check-modal"
            ]);
        }
        // 预览成品
        echo ' ' . Html::a(Yii::t('app', 'Preview'), null, [
            'class' => 'btn btn-primary',
            'target' => '_blank',
            'href' => Yii::$app->params['hellobabo']['ug_url'] . "?ogid=$model->id"
        ]);
        ?>
    </p>

    <!-- 基本信息 -->
    <?= $this->render('_view_baseinfo', ['model' => $model]) ?>
    <!-- 初始信息 -->
    <?= $this->render('_view_initinfo', ['model' => $model]) ?>
    <!-- 场景信息 -->
    <?= $this->render('_view_sceneinfo', ['model' => $model]) ?>
    <!-- 操作记录 -->
    <?= $this->render('_view_actionlog', ['model' => $model]) ?>
</div>
<!-- 解决 -->
<?= $this->render('_modal_check', ['url' => 'check', 'model' => $model]) ?>