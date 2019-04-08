<?php

use common\models\goods\Goods;
use common\utils\I18NUitl;
use kartik\growl\GrowlAsset;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

GrowlAsset::register($this);

/* @var $model Goods */

//加载 SpecItem DOM 模板
$spec_item_dom = str_replace("\n", ' ', $this->render('____spec_item_dom'));
$spec_item_price_tr_dom = str_replace("\n", ' ', $this->render('____spec_item_price_tr_dom'));
//所有规格项
$goodsSpecItems = $model->getGoodsSpecItems();
//所有已有价格
$initGoodsSpecPrices = $model->getGoodsSpecPrices();
?>
<div class="goods-spec-box">
    <!-- 规格 -->
    <div class="spec-table">
        <?=
        GridView::widget([
            'dataProvider' => new ArrayDataProvider(['allModels' => $model->goodsModel->goodsSpecs]),
            'tableOptions' => ['class' => 'table table-striped table-bordered wsk-table'],
            'layout' => "{items}\n{pager}",
            'columns' => [
                [
                    'attribute' => 'name',
                    'headerOptions' => ['style' => 'width:120px']
                ],
                [
                    'label' => I18NUitl::t('app', '{Spec}{Item}'),
                    'format' => 'raw',
                    'value' => function($model) {
                        return "<div class='spec-box' data-id='$model->id'></div>";
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'buttons' => [
                        'add' => function($url, $specModel) use($model) {
                            return Html::a(Yii::t('app', 'Add'), ['#'], ['class' => 'btn btn-default', 'onclick' => "createSpecItem($specModel->id,$model->id);return false;"]);
                        },
                    ],
                    'headerOptions' => ['style' => 'width:80px'],
                    'template' => '{add}',
                ],
            ]
        ]);
        ?>
    </div>

    <!-- 规格价格 -->
    <div class="spec-price-box">
        <p style="text-align: right;">
            <a class="btn btn-default"><?= I18NUitl::t('app', '{Batch}{Fill}') ?></a>
        </p>

        <table class="table table-striped table-bordered wsk-table">
            <thead>
                <tr>
                    <!-- 动态列 -->
                    <?php foreach ($model->goodsModel->goodsSpecs as $spec): ?>
                        <th><?= $spec->name ?></th>
                    <?php endforeach; ?>
                    <!-- 固定列 -->
                    <th><?= Yii::t('app', 'Image') ?></th>
                    <th><?= Yii::t('app', 'Des') ?></th>
                    <th><?= Html::textInput('spec_item_price_input', null, ['class' => 'form-control', 'placeholder' => Yii::t('app', 'Price Placeholder')]) ?></th>
                    <th><?= Html::textInput('spec_item_store_input', null, ['class' => 'form-control', 'placeholder' => Yii::t('app', 'Store Placeholder')]) ?></th>
                    <th><?= Yii::t('app', 'Action') ?></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- 添加规格项模态框 -->
    <div class="modal fade create-spec-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><?= I18NUitl::t('app', '{Add}{Spec}{Item}') ?></h4>
                </div>
                <div class="modal-body">
                    <?= Html::textInput('new_spec_item_input', '', ['class' => 'form-control']) ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary save"><?= Yii::t('app', 'Save') ?></button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>

<script>
    var goods_id = <?= $model->id ?>;
    var specs = <?= json_encode(ArrayHelper::getColumn($model->goodsModel->goodsSpecs, 'id')) ?>;
    //规格项Dom
    var spec_item_dom = <?= json_encode($spec_item_dom) ?>;
    var spec_item_price_tr_dom = <?= json_encode($spec_item_price_tr_dom) ?>;
    var spec_items_map = <?= json_encode(ArrayHelper::index($goodsSpecItems, null, 'spec_id')) ?>;

    //已经存在的规格项
    var spec_items = {};
    //所有规格价格
    var spec_item_prices = [];


    window.onload = function () {
        initSpec();
        initSpecPrice();
    }

    /**
     * 初始所有规格
     * @returns {void}
     */
    function initSpec() {
        var init_spec_items = <?= json_encode($goodsSpecItems) ?>;
        $.each(init_spec_items, function (index, item) {
            addSpecItem(item);
        });
    }
</script>

<script>
    /**
     * 新增一个规格项
     * @param {int} specId  规格ID
     * @param {int} goodsId 商品ID
     * @returns {void}
     */
    function createSpecItem(specId, goodsId)
    {
        $modal = $('.create-spec-modal');
        $input = $modal.find('input[name=new_spec_item_input]');
        $input.val('');
        $modal.modal('show');
        $modal.find('.save').one('click', function () {
            $this = $(this);
            $this.addClass('disabled');
            $.ajax({
                type: "POST",
                url: 'add-spec-item',
                data: {
                    SpecItem: {
                        spec_id: specId,
                        goods_id: goodsId,
                        value: $input.val()
                    }},
                success: function (r, textStatus) {
                    $this.removeClass('disabled');
                    if (r.code == '0') {
                        //成功
                        $.notify({message: '操作成功！'}, {type: 'success'});
                        //重新刷新页面
                        addSpecItem(r.data);
                        addSpecItemToMap(r.data);
                    } else {
                        //错误
                        $.notify({message: '操作失败！\n' + r.msg}, {type: 'danger'});
                    }
                },
                error: function (e) {
                    $this.removeClass('disabled');
                    $.notify({message: '网络错误！'}, {type: 'danger'});
                }
            });
            $('.create-spec-modal').modal('hide');
        });
    }

    /**
     * 删除规格项
     * @param {int} specItemId
     * @returns {void}
     */
    function removeSpecItem(specItemId) {
        $.ajax({
            type: "POST",
            url: 'del-spec-item?id=' + specItemId,
            success: function (r, textStatus) {
                if (r.code == '0') {
                    //成功
                    $.notify({message: '操作成功！'}, {type: 'success'});
                    //重新刷新页面
                    delSpecItem(specItemId);
                    delSpecItemToMap(specItemId);
                } else {
                    //错误
                    $.notify({message: '操作失败！\n' + r.msg}, {type: 'danger'});
                }
            },
            error: function (e) {
                $.notify({message: '网络错误！'}, {type: 'danger'});
            }
        });
    }

    /**
     * 添加规格项显示
     * @param {array} item
     * @returns {void}
     */
    function addSpecItem(item) {
        //创建VO
        var specItem = new SpecItemVO(item);
        spec_items[item.id] = specItem;
        $(specItem).on('changed', function () {
            reflashItemPrice();
        });
        //显示
        $specBox = $(".spec-box[data-id=" + item.spec_id + "]");
        $specItem = $(Wskeee.StringUtil.renderDOM(spec_item_dom, item)).appendTo($specBox);
        $specItem.find('.close').on('click', function (e) {
            removeSpecItem($(this).parent().attr('data-id'));
            return false;
        });
        $specItem.on('click', function () {
            $(this).toggleClass('selected');
            specItem.setSelected($(this).hasClass('selected'));
        });
    }

    /**
     * 删除规格项显示
     * @param {int} spec_item_id
     * @returns {undefined}
     */
    function delSpecItem(spec_item_id) {
        delete spec_items[spec_item_id];
        $specItem = $('.item-box[data-id=' + spec_item_id + ']');
        $specItem.remove();
    }
</script>

<script>

    /**
     * 初始已有价格
     * @returns {void}
     */
    function initSpecPrice() {
        var initGoodsSpecPrices = <?= json_encode($initGoodsSpecPrices) ?>
        //找出已经选择的规格，先拿到所有spec_key（k_k_k）
        var spec_key = <?= json_encode(array_filter(explode('_',implode('_', ArrayHelper::getColumn($initGoodsSpecPrices, 'spec_key'))))) ?>
        //填充数据
        $.each(initGoodsSpecPrices,function(index,item){
            var spvo = SpecItemSpriteVO.getInstance(item.goods_id,item.spec_key,item.spec_key_name);
            spvo.load(item);
        });
    }
    /**
     * 添加specItem 到map
     * @param {object} item
     * @returns {void}
     */
    function addSpecItemToMap(item) {
        var specItmes = spec_items_map[item.spec_id];
        if (specItmes && specItmes.length > 0) {
            specItmes.push(item);
        } else {
            spec_items_map[item.spec_id] = [item];
        }
    }

    /**
     * 从map里删除item
     * @param {Object} item
     * @returns {void}
     */
    function delSpecItemToMap(item_id) {
        var item = spec_items[item_id];
        var specItmes = spec_items_map[item.spec_id];
        for (var i = 0, len = specItmes.length; i < len; i++) {
            if (specItmes[i].id == item_id) {
                specItmes.splice(i, 1);
                break;
            }
        }
    }

    /**
     * 生成价格数据
     * @returns {void}
     */
    function createItemPrices() {
        var itemPrices = null;
        $.each(spec_items_map, function (key, items) {
            itemPrices = _createItemPrice(itemPrices, items);
        });
        spec_item_prices = [];
        $table = $('.spec-price-box .table');
        $tbody = $('.spec-price-box .table tbody');
        $tbody.empty();
        $.each(itemPrices, function (spec_key, spec_key_name) {
            spec_item_prices[spec_key] = SpecItemSpriteVO.getInstance(goods_id, spec_key, spec_key_name);
            //添加到表中显示
            $tbody.append(_createItemPriceTrDom(spec_item_prices[spec_key]));
        });
        //合并相同列
        if (specs.length >= 2) {
            for (var i = specs.length - 2; i >= 0; i--) {
                autoRowSpan($table[0], 0, i);
            }
        }
    }

    /**
     * 生成规格价格tr
     * @param {SpecItemSpriteVO} itemPrice
     * @returns {Dom}
     */
    function _createItemPriceTrDom(itemPrice) {
        var $tr = $(Wskeee.StringUtil.renderDOM(spec_item_price_tr_dom, itemPrice));
        var d_tr = '';
        var spec_keys = itemPrice.spec_key.split('_');
        var spec_key_names = itemPrice.spec_key_name.split('_');
        //添加动态列
        $.each(spec_key_names, function (index, item) {
            d_tr += '<td>' + item + '</td>';
        });
        $(d_tr).prependTo($tr);
        return $tr;
    }
    /**
     * 自动合并table行列
     * @param {Table} tb 
     * @param {int} row     行
     * @param {int} col     列
     * @returns {void}     
     **/
    function autoRowSpan(tb, row, col) {
        var lastValue = "";
        var value = "";
        var pos = 1;
        for (var i = row; i < tb.rows.length; i++) {
            value = tb.rows[i].cells[col].innerText;
            if (lastValue == value) {
                tb.rows[i].deleteCell(col);
                tb.rows[i - pos].cells[col].rowSpan = tb.rows[i - pos].cells[col].rowSpan + 1;
                pos++;
            } else {
                lastValue = value;
                pos = 1;
            }
        }
    }


    /**
     * 生成价格，只生成已选择规格项
     * @param {Object} prices SpectItemPrice
     * @param {Array} items SpecItem
     * @returns {Object} SpectItemPrice
     */
    function _createItemPrice(prices, items) {
        if (!prices) {
            //第一次
            prices = {};
            $.each(items, function (index, item) {
                if (spec_items[item.id].selected) {
                    prices[item.id] = item.value;
                }
            });
        } else {
            $.each(prices, function (key, value) {
                $.each(items, function (index, item) {
                    if (spec_items[item.id].selected) {
                        prices[key + "_" + item.id] = value + "_" + item.value;
                        delete prices[key];
                    }
                });
            });
        }

        return prices;
    }
    /**
     * 刷新规格价格显示
     * @returns {void}
     */
    function reflashItemPrice() {
        //必须所有规格都有值
        for (var i = 0, len = specs.length; i < len; i++) {
            if ($('.spec-box[data-id=' + specs[i] + '] .selected').length == 0) {
                return;
            }
        }
        //生成
        createItemPrices();
    }
</script>

<script>
    /**
     * SpecItem 规格模型
     * @param {array} config    [id,spec_id,goods_id,value]
     * @returns {SpecItemVO}
     */
    function SpecItemVO(config) {
        $.extend(this, config);
        this.selected = false;
    }

    SpecItemVO.prototype.setSelected = function (bo) {
        this.selected = bo;
        $(this).trigger('changed');
    }
</script>

<script>
    /**
     * SpecItemPrice 规格价格模型
     * @param {array} config    [id,spec_id,goods_id,value]
     * @returns {SpecItemVO}
     */
    function SpecItemSpriteVO(goods_id, spec_key, spec_key_name) {
        this.goods_id = goods_id;
        this.spec_key = spec_key;
        this.spec_key_name = spec_key_name;
        this.goods_const = 0;
        this.goods_price = 0;
        this.spec_img_url = '';
        this.spec_des = '';
        this.store_count = 0;

        this.selected = false;
    }

    SpecItemSpriteVO.instances = [];
    SpecItemSpriteVO.getInstance = function (goods_id, spec_key, spec_key_name) {
        if (!SpecItemSpriteVO.instances[spec_key]) {
            SpecItemSpriteVO.instances[spec_key] = new SpecItemSpriteVO(goods_id, spec_key, spec_key_name);
        }
        return SpecItemSpriteVO.instances[spec_key];
    }
    
    SpecItemSpriteVO.prototype.load = function(data){
        $.extend(this,data);
    }

    SpecItemSpriteVO.prototype.setSelected = function (bo) {
        this.selected = bo;
        $(this).trigger('changed');
    }
</script>
