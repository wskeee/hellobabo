<?php

use backend\modules\order_admin\assets\OrderModuleAsset;
use common\models\order\WorkflowDesign;
use common\modules\rbac\components\ResourceHelper;
use common\utils\I18NUitl;
use common\widgets\btnloader\BtnLoaderAsset;
use kartik\growl\GrowlAsset;
use yii\web\View;
use yii\web\YiiAsset;

OrderModuleAsset::register($this);
YiiAsset::register($this);

/* @var $this View */
/* @var $model WorkflowDesign */

$this->title = I18NUitl::t('app', '{Design}{Detail}：') . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Workflow Designs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="workflow-design-view">

    <p>
        <?php
        if ($model->status == WorkflowDesign::STATUS_WAIT_START) {
            //开始
            echo ResourceHelper::a(I18NUitl::t('app', '{Start}{Design}'), ['start', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', '你确定要做该操作吗？'),
                    'data' => 'post',
            ]]);
        } else if ($model->status == WorkflowDesign::STATUS_RUNGING) {
            //结束
            echo ResourceHelper::a(I18NUitl::t('app', '{End}{Design}'), ['end', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', '你确定要做该操作吗？'),
                    'data' => 'post',
            ]]);
        }
        ?>
    </p>

    <!-- 基本信息 -->
    <?= $this->render('_view_baseinfo', ['model' => $model]) ?>
    <!-- 初始信息 -->
    <?= $this->render('_view_initinfo', ['model' => $model]) ?>
    <!-- 场景信息 -->
    <?= $this->render('_view_sceneinfo', ['model' => $model]) ?>

</div>