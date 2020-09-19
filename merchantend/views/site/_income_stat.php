<?php

use common\models\shop\Shop;
use common\widgets\charts\ChartAsset;

ChartAsset::register($this);

$api_url = Yii::$app->params['api_url'];
$shop = Shop::findOne(['user_id' => Yii::$app->user->id]);

?>

<div class="content-box">
    <div class="panel form-group month" style="padding: 10px;">
        <div class="head" style="border-bottom: dashed 1px #d4dced;">
            <div>
                <span class="title">月收益：</span>
                <span class="num">（0 元）</span>
            </div>
            <select id="income-stat-month" onchange="onMonthStatChange()">
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?= $i>9?$i:'0'.$i ?>"><?= $i ?>月</option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="body" style="height: 300px;">
            <div class="chart-box month-income"></div>
        </div>
    </div>
</div>

<div class="content-box" style="margin-top: 10px;">
    <div class="panel form-group year" style="padding: 10px;">
        <div class="head" style="border-bottom: dashed 1px #d4dced;">
            <div>
                <span class="title">年收益：</span>
                <span class="num">（0 元）</span>
            </div>
            <select id="income-stat-year" onchange="onYearStatChange()">
                <?php for ($i = 2020; $i <= 2025; $i++): ?>
                    <option value="<?= $i ?>"><?= $i ?>年</option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="body" style="height: 300px;">
            <div class="chart-box year-income"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var dailyIncomeChart;
    var yearIncomeChart;

    function onMonthStatChange() {
        var year = '<?= date('Y', time()) ?>';
        var month = $('#income-stat-month').val();
        $.get("<?= $api_url ?>/v1/shop/get-income-stat", {shop_id: <?= $shop->id ?>,year:year,month:month}, function (r) {
                if (r.code == '0') {
                    var data = r.data.incomes;
                    $('.content-box .month .num').html(r.data.all_incomes+'元');
                    dailyIncomeChart.reflashChart(data);
                }
            },'json');
    }

    function onYearStatChange() {
        var year = $('#income-stat-year').val();
        $.get("<?= $api_url ?>/v1/shop/get-income-stat", {shop_id: <?= $shop->id ?>,year:year}, function (r) {
            if (r.code == '0') {
                var data = r.data.incomes;
                $('.content-box .year .num').html(r.data.all_incomes+'元');
                yearIncomeChart.reflashChart(data);
            }
        },'json');
    }

    window.onload = function () {
        var month = '<?= date('m', time()) ?>';
        var year = '<?= date('Y', time()) ?>';
        $('#income-stat-month').val(month);
        $('#income-stat-month').trigger('change');

        $('#income-stat-year').val(year);
        $('#income-stat-year').trigger('change');

        dailyIncomeChart = new wskcharts.DragBarChart({}, $('.content-box .month-income')[0], []);
        yearIncomeChart = new wskcharts.DragBarChart({}, $('.content-box .year-income')[0], []);
    }

</script>