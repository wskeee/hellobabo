<?php

use backend\modules\order_admin\assets\OrderModuleAsset;
use common\models\order\WorkflowPrint;
use common\modules\rbac\components\ResourceHelper;
use common\utils\I18NUitl;
use yii\helpers\Html;
use yii\web\View;
use yii\web\YiiAsset;

YiiAsset::register($this);
OrderModuleAsset::register($this);
/* @var $this View */
/* @var $model WorkflowPrint */

$this->title = I18NUitl::t('app', '{Print}{Detail}：') . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Prints'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<script>
    var callbacks = [];
    window.addOnReady = function (cb) {
        callbacks.push(cb);
    }
    window.onload = function () {
        for (var i = 0, len = callbacks.length; i < len; i++) {
            callbacks[i]();
        }
    }
</script>
<div class="workflow-print-view">

    <p>
        <?php
        if ($model->status == WorkflowPrint::STATUS_WAIT_START) {
            //开始
            echo ResourceHelper::a(I18NUitl::t('app', '{Start}{Print}'), ['start', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', '你确定要做该操作吗？'),
                    'data' => 'post',
            ]]);
        } else if ($model->status == WorkflowPrint::STATUS_RUNGING || $model->status == WorkflowPrint::STATUS_CHECK_FAIL) {
            //结束
            echo ResourceHelper::a(I18NUitl::t('app', '{End}{Print}'), ['end', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', '你确定要做该操作吗？'),
                    'data' => 'post',
            ]]);
        }
        // 预览成品
        echo ' ' . Html::a(Yii::t('app', 'Preview'), null, [
            'class' => 'btn btn-primary',
            'target' => '_blank',
            'href' => Yii::$app->params['hellobabo']['ug_url'] . "?ogid=$model->order_goods_id"
        ]);
        ?>
    </p>

    <!-- 基本信息 -->
    <?= $this->render('_view_baseinfo', ['model' => $model]) ?>
    <!-- 场景信息 -->
    <?= $this->render('_view_sceneinfo', ['model' => $model]) ?>
    <!-- 日志信息 -->
    <?= $this->render('_view_actionlog', ['model' => $model]) ?>

</div>
