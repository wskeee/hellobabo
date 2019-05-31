<?php

use common\models\order\WorkflowPrint;
use common\modules\rbac\components\ResourceHelper;
use common\utils\I18NUitl;
use yii\web\View;
use yii\web\YiiAsset;

/* @var $this View */
/* @var $model WorkflowPrint */

$this->title = I18NUitl::t('app', '{Print}{Detail}：').$model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Prints'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
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
        } else if ($model->status == WorkflowPrint::STATUS_RUNGING) {
            //结束
            echo ResourceHelper::a(I18NUitl::t('app', '{End}{Print}'), ['end', 'id' => $model->id], [
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
    <!-- 场景信息 -->
    <?= $this->render('_view_sceneinfo', ['model' => $model]) ?>

</div>
