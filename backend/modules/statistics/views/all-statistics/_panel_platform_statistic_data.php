<?php

use kartik\widgets\Select2;

?>

<div class="statistic-container wsk-panel">
    <div class="title">
        <span>实例汇总</span>
        <div class="pull-right">
            <!-- 条件 -->
            <div class="dep-dropdown-box">
                <div class="dep-dropdown" style="width:120px">
                    <?=
                    Select2::widget([
                        'id' => 'statistic_year',
                        'name' => 'statistic_year',
                        'data' => $years,
                        'options' => ['placeholder' => Yii::t('app', 'Year')],
                        'pluginOptions' => ['allowClear' => true],
                        'pluginEvents' => ['change' => 'function(){ onStatisticDataChange()}']
                    ])
                    ?>
                </div>
            </div>
            <!-- 条件 -->
            <div class="dep-dropdown-box">
                <div class="dep-dropdown" style="width:120px">
                    <?=
                    Select2::widget([
                        'id' => 'statistic_month',
                        'name' => 'statistic_month',
                        'data' => $months,
                        'options' => ['placeholder' => Yii::t('app', 'Month')],
                        'pluginOptions' => ['allowClear' => true],
                        'pluginEvents' => ['change' => 'function(){ onStatisticDataChange()}']
                    ])
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="body">
        <!-- 单数 -->
        <div class="item-box">
            <div class="i-head"><i class="glyphicon glyphicon-list-alt"></i></div>
            <div class="i-body">
                <span><?= Yii::t('app', 'Sale Count') ?></span>
                <span class="sale_count">0 单</span>
            </div>
        </div>
        <!-- 营业额 -->
        <div class="item-box">
            <div class="i-head"><i class="glyphicon glyphicon-yen"></i></div>
            <div class="i-body">
                <span><?= Yii::t('app', 'Turnover') ?></span>
                <span class="turnover">0 元</span>
            </div>
        </div>
        <!-- 推广费 -->
        <div class="item-box">
            <div class="i-head"><i class="glyphicon glyphicon-yen"></i></div>
            <div class="i-body">
                <span><?= Yii::t('app', 'Promotion Expenses Count') ?></span>
                <span class="promotion_expenses_count">0 元</span>
            </div>
        </div>
        <!-- 推广费 -->
        <div class="item-box">
            <div class="i-head"><i class="glyphicon glyphicon-yen"></i></div>
            <div class="i-body">
                <span><?= Yii::t('app', 'Promotion Expenses') ?></span>
                <span class="promotion_expenses">0 元</span>
            </div>
        </div>
        <!-- 客户 -->
        <div class="item-box">
            <div class="i-head"><i class="glyphicon glyphicon-user"></i></div>
            <div class="i-body">
                <span><?= Yii::t('app', 'Client Count') ?></span>
                <span class="client_count">0 个</span>
            </div>
        </div>
    </div>
</div>
<script>
    function onStatisticDataChange() {
        var date = $('#statistic_year').val() + ($('#statistic_month').val() == '' ? "" : "-" + $('#statistic_month').val());

        $.get('get-platform-instance-statistic?date=' + date, function (r) {
            var data = r.data;
            $('.statistic-container .sale_count').html(data['sale_count'] + ' 单');
            $('.statistic-container .turnover').html(data['turnover'] + ' 元');
            $('.statistic-container .promotion_expenses_count').html(data['promotion_expenses']['count'] + ' 次');
            $('.statistic-container .promotion_expenses').html(data['promotion_expenses']['amount'] + ' 元');
            $('.statistic-container .goods_count').html(data['goods_count'] + ' 个');
            $('.statistic-container .client_count').html(data['client_count'] + ' 个');
        });
    }

    window.addLoadEvent(onStatisticDataChange);
</script>