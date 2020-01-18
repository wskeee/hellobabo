<?php

use common\utils\I18NUitl;
?>
<div class="income-overview wsk-panel">
    <div class="title">收益概况</div>
    <div class="body">
        <table class="table table-striped table-bordered wsk-table">
            <thead>
                <tr>
                    <th><?= I18NUitl::t('app', '{Today}{Sale Count}') ?></th>
                    <th><?= I18NUitl::t('app', '{Today}{Turnover}') ?></th>
                    <th><?= I18NUitl::t('app', '{This Month}{Sale Count}') ?></th>
                    <th><?= I18NUitl::t('app', '{This Month}{Turnover}') ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="today_sale_sale_count">0</td>
                    <td class="today_sale_turnover">0</td>
                    <td class="month_sale_sale_count">0</td>
                    <td class="month_sale_turnover">0</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<script>
    window.addLoadEvent(function(){
        $.get('get-platform-this-day-month-sale', function (r) {
            var data = r.data;
            $('.income-overview .today_sale_sale_count').html(data['today_sale']['sale_count']+' 单');
            $('.income-overview .today_sale_turnover').html(data['today_sale']['turnover']+' 元');
            $('.income-overview .month_sale_sale_count').html(data['month_sale']['sale_count']+' 单');
            $('.income-overview .month_sale_turnover').html(data['month_sale']['turnover']+' 元');
        });
    });
</script>