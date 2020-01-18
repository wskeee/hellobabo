<?php

use backend\modules\statistics\assets\StatisticsModuleAsset;
use yii\web\View;

StatisticsModuleAsset::register($this);

/* @var $this View */

$this->title = '平台统计';
$years = [];
$months = ['01' => '1月', '02' => '2月', '03' => '3月', '04' => '4月', '05' => '5月', '06' => '6月',
    '07' => '7月', '08' => '8月', '09' => '9月', '10' => '10月', '11' => '11月', '12' => '12月',];
for ($i = 2019; $i <= date('Y'); $i++) {
    $years [$i] = $i;
}
?>

<script>
    var windowOnloadCallBacks = [];
    function addLoadEvent(func) {
        windowOnloadCallBacks.push(func);
    }
    window.addLoadEvent = addLoadEvent;
    window.onload = function () {
        for (var i = 0, len = windowOnloadCallBacks.length; i < len; i++) {
            windowOnloadCallBacks[i]();
        }
    }
</script>

<div class="statistic-admin statistic">
    <!-- 平台实例数据统计 -->
    <?= $this->render('_panel_platform_statistic_data', ['years' => $years, 'months' => $months]) ?>
    <!-- 平台日月统计 -->
    <?= $this->render('_panel_platform_income_overview') ?>
    <!-- 流程统计 -->
    <?= $this->render('_panel_platform_workflow_overview') ?>
    <!-- 平台每日收益 -->
    <!-- 平台设备使用率 -->
    <!-- 平台用户转化率 -->
    <!-- 平台用户日增长速度 -->
    <!-- 平台用户月增长速度 -->
    <?= $this->render('_panel_platform_trend_statistic') ?>

</div>