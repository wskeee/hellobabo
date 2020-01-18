<?php

use common\widgets\charts\ChartAsset;

ChartAsset::register($this);
?>
<div class="trend-statistic wsk-panel">
    <div class="title">
        <span>趋势分析</span>
        <div class="pull-right">
            <button class="btn btn-default" onclick="refresh(7)">最近7天</button>
            <button class="btn btn-default" onclick="refresh(30)">最近30天</button>
        </div>
    </div>
    <div class="body chart-container">
        <div>
            <!-- 平台每日收益 -->
            <div>
                <p>每日收益</p>
                <div class="chart-box daily-income"></div>
            </div>

            <!-- 平台设备使用率 -->
            <div>
                <p>设备使用率</p>
                <div class="chart-box goods-usage"></div>
            </div>
        </div>

        <div>
            <!-- 平台用户访问量 -->
            <div>
                <p>用户访问量</p>
                <div class="chart-box daily-vt"></div>
            </div>
            
            <!-- 平台用户转化率 -->
            <div>
                <p>用户转化率</p>
                <div class="chart-box ucr"></div>
            </div>
        </div>
    </div>
</div>
<script>
    var dailyIncomeChart,
            goodsUsageChart,
            ucrChart,
            dailyVtChart;

    window.addLoadEvent(function () {
        dailyIncomeChart = new wskcharts.LineStackChart({
            'series.name': '每日营业额',
            'axisLabel.formatter': '{value}元'
        }, $('.trend-statistic .daily-income')[0], []);
        goodsUsageChart = new wskcharts.LineStackChart({
            'series.name': '买次数',
            'axisLabel.formatter': '{value}次'
        }, $('.trend-statistic .goods-usage')[0], []);
        ucrChart = new wskcharts.LineStackChart({
            'series.name': '用户转化率',
            'axisLabel.formatter': '{value}%'
        }, $('.trend-statistic .ucr')[0], []);
        dailyVtChart = new wskcharts.LineStackChart({
            'series.name': '用户访问量',
            'axisLabel.formatter': '{value}次'
        }, $('.trend-statistic .daily-vt')[0], []);

        refresh(7);
    });

    function refresh(day_num) {
        $.get('get-platform-daily-income?day_num=' + day_num, function (r) {
            dailyIncomeChart.reflashChart(r.data);
        });
        $.get('get-platform-goods-usage?day_num=' + day_num, function (r) {
            goodsUsageChart.reflashByMultiple(r.data);
        });
        $.get('get-platform-ucr?day_num=' + day_num, function (r) {
            ucrChart.reflashChart(r.data);
        });
        $.get('get-platform-daily-vt?day_num=' + day_num, function (r) {
            dailyVtChart.reflashChart(r.data);
        });
    }

</script>