<?php

use common\utils\I18NUitl;
?>
<div class="workflow-overview wsk-panel">
    <div class="title">工作流概况</div>
    <div class="body">
        <table class="table table-striped table-bordered wsk-table">
            <thead>
                <tr>
                    <th><?= I18NUitl::t('app', '{Flow}') ?></th>
                    <th><?= I18NUitl::t('app', '{Today}{Increased}') ?></th>
                    <th><?= I18NUitl::t('app', '{Today}{Completed}') ?></th>
                    <th><?= I18NUitl::t('app', '{This Month}{Increased}') ?></th>
                    <th><?= I18NUitl::t('app', '{This Month}{Completed}') ?></th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>
<script>
    window.addLoadEvent(function(){
        $.get('get-platform-workflow-stat', function (r) {
            var data = r.data;
            for(var i=0,len=data.length;i<len;i++){
                var item = data[i];
                $tr = "<tr>\n" +
                    "<td class=\"flow_name\">"+item['flow_name']+"</td>\n" +
                    "<td class=\"today_increase_count\">"+item['today_stat']['create_count']+"</td>\n" +
                    "<td class=\"today_complete_count\">"+item['today_stat']['complete_count']+"</td>\n" +
                    "<td class=\"month_increase_count\">"+item['month_stat']['create_count']+"</td>\n" +
                    "<td class=\"month_complete_count\">"+item['month_stat']['complete_count']+"</td>\n" +
                    "</tr>";

                $('.workflow-overview table > tbody').append($tr);
            }

        });
    });
</script> 