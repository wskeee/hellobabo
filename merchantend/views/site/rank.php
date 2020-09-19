<?php

use common\models\goods\GoodsCategory;
use common\models\shop\Shop;
use common\widgets\charts\ChartAsset;
use kartik\widgets\Select2;
use merchant\assets\SiteAssets;
use yii\helpers\ArrayHelper;
use yii\web\View;

/** @var $this View */
/** @var $shop Shop */
/** @var $api_url string */
/** @var $categorys array<GoodsCategory> */

$this->title = Yii::$app->name;

SiteAssets::register($this);

?>
<style>

</style>
<div class="site-rank">
    <div class="content-box">
        <div class="panel form-group month">
            <div class="head">
                <div>
                    <select id="rank-year" onchange="onSearchChange()">
                        <option value="" selected>全部</option>
                        <?php for ($i = 2020; $i <= 2025; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?>年</option>
                        <?php endfor; ?>
                    </select>
                    <select id="rank-month" onchange="onSearchChange()">
                        <option value="" selected>全部</option>
                        <?php for ($i = 1; $i <= 12; $i++): ?>
                            <option value="<?= $i > 9 ? $i : '0' . $i ?>"><?= $i ?>月</option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div>
                    <select id="rank-category" onchange="onSearchChange()">
                        <option value="" selected>总排行榜</option>
                        <?php foreach ($categorys as $category): ?>
                            <option value="<?= $category->id ?>"><?= $category->name ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select id="rank-type" onchange="onSearchChange()">
                        <option value="income" selected>按收益</option>
                        <option value="count">按销量</option>
                    </select>
                </div>
            </div>
            <div class="body">
                <div class="tabel-box">
                    <table id="e-table" class="e-table" style="font-size: 12px;">
                        <thead>
                        <tr>
                            <th>排名</th>
                            <th>绘本名</th>
                            <th>类型</th>
                            <th class="th-type">收益</th>
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
</div>

<script type="text/html" id="row-tpl">
    <tr>
        <td class="num"></td>
        <td class="goods-name"></td>
        <td class="category"></td>
        <td class="income"></td>
    </tr>
</script>

<script type="text/javascript">
    var get_list_url = "<?= $api_url ?>/v1/shop/get-rank";
    var page = 1;
    var page_size = 10;
    var total = 0;
    var params = {};
    var is_loading = false;
    var categorys = <?= json_encode(ArrayHelper::map($categorys, 'id', 'name')); ?>;
    console.log(categorys);

    /**
     * 条件更换时
     */
    function onSearchChange() {
        params = {
            year: $('#rank-year').val(),
            month: $('#rank-month').val(),
            type: $('#rank-type').val(),
            category: $('#rank-category').val(),
        };
        $('#e-table tbody').empty();
        getList(params, 1, page_size);
    }

    /**
     * 下一页
     */
    function nextPage() {
        var max_page = Math.ceil(total / page_size);
        if (page < max_page) {
            getList(params, page + 1, page_size);
        }
    }

    /**
     * 获取列表
     * @param params
     * @param p
     * @param p_size
     */
    function getList(params, p, p_size) {
        if (is_loading) return;
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
                    row.find('.category').html(categorys[item['category_id']]);
                    row.find('.income').html(item['value']);
                    table_body.append(row);
                }

                var max_page = Math.ceil(total / page_size);
                if (page >= max_page) {
                    $('.loading-box').html('没有更多了');
                }
            }
        }, 'json');
    }

    window.onload = function () {
        var month = '<?= date('m', time()) ?>';
        var year = '<?= date('Y', time()) ?>';
        $('#rank-month').val(month);
        $('#rank-year').val(year).trigger('change');

        $(window).scroll(function () {
            var windowH = $(window).height();//设备可见区域高度
            var documentH = $(document).height();//整个网页的高度（包括未显示的部分）
            var scrollH = $(window).scrollTop();//滚动条滚动上去的高度
            //或者  scrollH = $(document).scrollTop();
            if (windowH + scrollH >= documentH) {
                nextPage();
            }
        });
    }

</script>
