<?php

use common\models\order\WorkflowDesign;
use common\modules\rbac\components\ResourceHelper;
use common\utils\I18NUitl;
use yii\web\View;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

YiiAsset::register($this);

/* @var $this View */
/* @var $model WorkflowDesign */

$this->title = $model->id;
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
            ResourceHelper::a(I18NUitl::t('app', '{End}{Design}'), ['end', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', '你确定要做该操作吗？'),
                    'data' => 'post',
            ]]);
        }
        ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'order_id',
            'order_sn',
            'status',
            'start_at',
            'end_at',
            'worker_id',
            'created_at',
            'updated_at',
        ],
    ])
    ?>

</div>
