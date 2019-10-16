<?php

use backend\modules\order_admin\assets\OrderModuleAsset;
use common\models\goods\Goods;
use common\models\order\WorkflowDesign;
use common\modules\rbac\components\ResourceHelper;
use common\utils\I18NUitl;
use yii\helpers\Html;
use yii\web\View;
use yii\web\YiiAsset;

OrderModuleAsset::register($this);
YiiAsset::register($this);

/* @var $this View */
/* @var $model WorkflowDesign */

$this->title = I18NUitl::t('app', '{Design}{Detail}：') . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Workflow Designs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$isGroupon = $model->orderGoods->type == Goods::TYPE_GROUPON;
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
<div class="workflow-design-view">
    <?php if ($isGroupon): ?>
        <div class="alert alert-warning" role="alert">【注意】该任务为团购单，团购内所有订单的设计必须由一个人完成，接受【开始】表示你接受该团所有订单的设计！！！</div>
    <?php endif; ?>
    <p>
        <?php
        if ($model->status == WorkflowDesign::STATUS_WAIT_START) {
            //开始
            echo ResourceHelper::a(I18NUitl::t('app', '{Start}{Design}'), [$isGroupon ? 'batch-start' : 'start', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', '你确定要做该操作吗？'),
                    'data' => 'post',
            ]]);
        } else if ($model->status == WorkflowDesign::STATUS_RUNGING || $model->status == WorkflowDesign::STATUS_CHECK_FAIL) {
            //结束
            echo ResourceHelper::a(I18NUitl::t('app', '{End}{Design}'), ['end', 'id' => $model->id], [
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
    <!-- 团购信息 -->
    <?= $model->orderGoods->type == Goods::TYPE_GROUPON ? $this->render('_view_grouponinfo', ['model' => $model]) : '' ?>
    <!-- 基本信息 -->
    <?= $this->render('_view_baseinfo', ['model' => $model]) ?>
    <!-- 封面信息 -->
    <?= $this->render('_view_coverinfo', ['model' => $model]) ?>
    <!-- 初始信息 -->
    <?= $this->render('_view_initinfo', ['model' => $model]) ?>
    <!-- 场景信息 -->
    <?= $this->render('_view_sceneinfo', ['model' => $model]) ?>
    <!-- 日志信息 -->
    <?= $this->render('_view_actionlog', ['model' => $model]) ?>

</div>