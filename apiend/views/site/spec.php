<?php
$specs = [
        ['id' => 1, 'name' => 'A', 'items' => [1, 2]],
        ['id' => 2, 'name' => 'B', 'items' => [3, 6, 7]],
        ['id' => 3, 'name' => 'C', 'items' => [4, 5]],
];

$gsps = [
        ['spec_key' => '1_3_4', 'store_count' => 0],
        ['spec_key' => '1_3_5', 'store_count' => 0],
        ['spec_key' => '1_4_6', 'store_count' => 1],
        ['spec_key' => '1_5_6', 'store_count' => 1],
        ['spec_key' => '1_4_7', 'store_count' => 1],
        ['spec_key' => '1_5_7', 'store_count' => 1],
        ['spec_key' => '2_3_4', 'store_count' => 1],
        ['spec_key' => '2_3_5', 'store_count' => 1],
        ['spec_key' => '2_4_6', 'store_count' => 0],
        ['spec_key' => '2_5_6', 'store_count' => 0],
        ['spec_key' => '2_4_7', 'store_count' => 1],
        ['spec_key' => '2_5_7', 'store_count' => 1],
];
?>
<style>
    .item{
        display: inline-block;
        padding: 10px;
        border: solid 1px #e4e4e4;
        cursor: pointer;
        color: #e4e4e4;
    }
    .can-selected{
        border: solid 1px #28b28b;
        color: #28b28b;
    }
    .selected {
        background: #28b28b;
        border: solid 1px #28b28b;
        color: #fff;
    }
</style>
<?php foreach ($specs as $index => $spec): ?>
    <div>
        <div><?= $spec['name'] ?></div>
        <div class="item-box">
    <?php foreach ($spec['items'] as $name): ?>
                <div class="item" data-level="<?= $index ?>" data-id="<?= $name ?>" onclick="onItemClick(<?= $index ?>,<?= $name ?>)"><?= $name ?></div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endforeach; ?>


<script>
    var specs = <?= json_encode($specs) ?>;
    var gsps = <?= json_encode($gsps) ?>;
    var spec_key = "2_3_4";
    var spec_key_arr = spec_key.split("_");
    //所有规格项库存
    var spec_item_counts = [];

    window.onload = function () {
        refresh();
    }

    /**
     * 
     * @param {string} specItemId
     * @returns {object}
     */
    function getSpecItemChildCount(specItemId) {
        $spec_item_count = {};
        $(gsps).each(function (index, gsp) {
            var gsp_key = gsp.spec_key;
            var spec_item_ids = gsp_key.split('_');
            if (!specItemId || gsp_key.indexOf(specItemId + "_") == 0) {
                $(spec_item_ids).each(function (i, id) {
                    if ($spec_item_count[id] == undefined) {
                        $spec_item_count[id] = 0;
                    }
                    $spec_item_count[id] += gsp.store_count;
                });
            }
        });
        return $spec_item_count;
    }

    function refresh() {
        console.log(spec_key);
        $('.item').each(function () {
            var id = $(this).html();
            if (spec_key_arr.indexOf(id) != -1) {
                $(this).addClass('selected');
            } else {
                $(this).removeClass('selected');
            }
        });

        //检查库存，先检查顶层再按已选择的规格一层一层往下检查
        $(spec_key_arr).each(function (index, id) {
            var ids = spec_key_arr.slice(0, index);
            var counts = getSpecItemChildCount(index == 0 ? null : ids.join("_"));
            var items = specs[index].items;
            console.log(ids.join("_"), counts);
            $(items).each(function (i, id) {
                spec_item_counts[id] = counts[id];
            });
        });
        $('.item').each(function () {
            var id = $(this).attr('data-id');
            if (spec_item_counts[id] != 0) {
                $(this).addClass('can-selected');
            } else {
                $(this).removeClass('can-selected');
            }

        });
    }

    function onItemClick(level, gsp_id) {
        spec_key_arr[level] = String(gsp_id);
        var spec_key_arr_sort = spec_key_arr.concat();
        spec_key_arr_sort.sort(function (a, b) {
            return a - b
        });
        spec_key = spec_key_arr_sort.join("_");
        refresh();
    }
</script>