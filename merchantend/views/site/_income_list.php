<?php

use common\models\shop\Shop;
use common\widgets\charts\ChartAsset;

ChartAsset::register($this);

$api_url = Yii::$app->params['api_url'];
$shop = Shop::findOne(['user_id' => Yii::$app->user->id]);

?>

<div class="content-box">
    <div class="panel form-group month">
        <div class="head" style="padding: 10px;border-bottom: solid 1px #d4dced;margin-top:0;">
            <div>
                <span class="title">总收益：</span>
                <span class="num all-income">（0 元）</span>
            </div>
            <div>
                <select id="income-year" onchange="onSearchChange()">
                    <?php for ($i = 2020; $i <= 2025; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?>年</option>
                    <?php endfor; ?>
                </select>
                <select id="income-month" onchange="onSearchChange()">
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?= $i>9?$i:'0'.$i ?>"><?= $i ?>月</option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>
        <div class="body">
            <div class="tabel-box">
                <table id="e-table" class="e-table" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th>序号</th>
                            <th>绘本名</th>
                            <th>购买人</th>
                            <th>收益</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="loading-box">
        <div class="loading"></div>
    </div>
</div>

<script type="text/html" id="row-tpl">
    <tr>
        <td class="num"></td>
        <td class="goods-name"></td>
        <td>
            <span class="user-name"></span>
            <br/>
            <span class="buy-time" style="color: #aaa"></span>
        </td>
        <td class="income"></td>
    </tr>
</script>

<script type="text/javascript">
    var get_list_url = "<?= $api_url ?>/v1/shop/get-income-list";
    var page = 1;
    var page_size = 20;
    var total = 0;
    var params = {};
    var is_loading = false;

    /**
     * 条件更换时
     */
    function onSearchChange() {
        params = {
            year:$('#income-year').val(),
            month:$('#income-month').val(),
        };
        $('#e-table tbody').empty();
        getList(params,1,page_size);
    }

    /**
     * 下一页
     */
    function nextPage() {
        var max_page = Math.ceil(total / page_size);
        if(page < max_page){
            getList(params, page + 1, page_size);
        }
    }

    /**
     * 获取列表
     * @param params
     * @param p
     * @param p_size
     */
    function getList(params,p,p_size){
        if(is_loading) return;
        is_loading = true;
        $('.loading-box').html('<div class="loading"></div>');
        params['shop_id'] = "<?= $shop->id ?>";
        params['page'] = p;
        params['page_size'] = p_size;
        $.get(get_list_url, params, function (r) {
            is_loading = false;
            if (r.code == '0') {
                page = Number(r.data.page);
                total = r.data.total;
                var row_tpl = $('#row-tpl').html();
                var table_body = $('#e-table tbody');
                var list = r.data.list;

                var row, item;
                for (var i = 0, len = list.length; i < len; i++) {
                    item = list[i];
                    row = $(row_tpl);
                    row.find('.num').html((page - 1) * page_size + (i + 1));
                    row.find('.goods-name').html(item['goods_name']);
                    row.find('.user-name').html(item['user_name']);
                    row.find('.buy-time').html(item['buy_time']);
                    row.find('.income').html(item['real_income']);
                    table_body.append(row);
                }

                $('.all-income').html(r.data.all_income + ' 元');

                var max_page = Math.ceil(total / page_size);
                if(page >= max_page){
                    $('.loading-box').html('没有更多了');
                }
            }
        },'json');
    }

    window.onload = function () {
        var month = '<?= date('m', time()) ?>';
        var year = '<?= date('Y', time()) ?>';
        $('#income-month').val(month);
        $('#income-year').val(year);
        $('#income-year').trigger('change');

        $(window).scroll(function(){
            var windowH=$(window).height();//设备可见区域高度
            var documentH=$(document).height();//整个网页的高度（包括未显示的部分）
            var scrollH=$(window).scrollTop();//滚动条滚动上去的高度
            //或者  scrollH = $(document).scrollTop();
            if(windowH+scrollH>=documentH){
                nextPage();
            }
        });
    }

</script>