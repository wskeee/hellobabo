<?php

use common\models\goods\Goods;
use common\utils\I18NUitl;
use common\widgets\webuploader\WebUploaderAsset;
use kartik\growl\GrowlAsset;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

GrowlAsset::register($this);
//获取flash上传组件路径
$sourcePath = $this->assetManager->getPublishedUrl(WebUploaderAsset::register($this)->sourcePath);
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
                    <th style = 'width:130px;'><?= Html::textInput('spec_item_price_input', null, ['class' => 'form-control', 'placeholder' => Yii::t('app', 'Price Placeholder')]) ?></th>
                    <th style = 'width:130px;'><?= Html::textInput('spec_item_store_input', null, ['class' => 'form-control', 'placeholder' => Yii::t('app', 'Store Placeholder')]) ?></th>
                    <th style = 'width:130px;'><?= Html::textInput('spec_item_scene_num_input', null, ['class' => 'form-control', 'placeholder' => Yii::t('app', 'Scene Num Placeholder')]) ?></th>
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
    // 空数据会转成数组，所以空数据时设置强制转换为对象
    var spec_items_map = <?= json_encode(ArrayHelper::index($goodsSpecItems, null, 'spec_id'), count($goodsSpecItems) == 0 ? JSON_FORCE_OBJECT : null) ?>;

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

    /**
     * 保存规格价格
     */
    function saveSpec() {
        var jons_specs = [];
        for (var i in spec_item_prices) {
            jons_specs.push(spec_item_prices[i]);
        }
        $.ajax({
            type: "POST",
            url: 'save-spec?goods_id=<?= $model->id ?>',
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify({specs: jons_specs}),
            success: function (r, textStatus) {
                if (r.code != '0') {
                    $.notify({message: '保存规格失败！\n' + r.msg}, {type: 'danger'});
                } else {
                    $.notify({message: '保存规格成功！'}, {type: 'success'});
                }
            },
            error: function (e) {
                $.notify({message: '保存规格失败！'}, {type: 'danger'});
            }
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
                    delSpecItemToMap(specItemId);
                    delSpecItem(specItemId);
                    reflashItemPrice();
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
        var initGoodsSpecPrices = <?= json_encode($initGoodsSpecPrices) ?>;
        //找出已经选择的规格，先拿到所有spec_key（k_k_k）
        var spec_keys = <?= json_encode(array_filter(explode('_', implode('_', ArrayHelper::getColumn($initGoodsSpecPrices, 'spec_key'))))) ?>;

        //填充数据
        $.each(initGoodsSpecPrices, function (index, item) {
            var spvo = SpecItemSpriteVO.getInstance(item.goods_id, item.spec_key, item.spec_key_name);
            spvo.load(item);
        });
        //设置规格选择
        $.each(spec_keys, function (i, spec_id) {
            $('.item-box[data-id=' + spec_id + ']').addClass('selected');
            spec_items[spec_id].setSelected(true, false);
        });
        reflashItemPrice();
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
     * 获取item对应名称
     * @param {array} item_ids
     * @returns {array}
     */
    function getItemName(item_ids) {
        var names = [];
        for (var i = 0, len = item_ids.length; i < len; i++) {
            names.push(spec_items[item_ids[i]]['value']);
        }
        return names;
    }

    /**
     * 生成价格数据
     * @returns {void}
     */
    function createItemPrices() {
        //规格价格项{id_id_id:value_value_value}
        var itemPrices = {};
        //id数组，[[1,2,3,4],[,2,3,4],[]...]
        //先拿到id数组，再作数组笛卡尔积运算
        var item_ids = [];
        $.each(spec_items_map, function (key, items) {
            var t_ids = [];
            $.each(items, function (index, item) {
                t_ids.push(item.id);
            });
            item_ids.push(t_ids);
        });
        //笛卡尔积运算
        var result = combineDika(item_ids);
        $.each(result, function (key, items) {
            //所有id升序排序
            items = items.sort(function (a, b) {
                return a - b
            });
            //合并id与值
            itemPrices[items.join('_')] = getItemName(items).join('_');
        });
        //生成表格
        spec_item_prices = [];
        $table = $('.spec-price-box .table');
        $tbody = $('.spec-price-box .table tbody');
        //销毁所有图片上传器
        destoryAllImgUploader();

        $tbody.empty();
        $.each(itemPrices, function (spec_key, spec_key_name) {
            spec_item_prices[spec_key] = SpecItemSpriteVO.getInstance(goods_id, spec_key, spec_key_name);
            //添加到表中显示
            $tbody.append(_createItemPriceTrDom(spec_item_prices[spec_key]));
            createImgUploader(spec_key, spec_item_prices[spec_key]['spec_img_url']);
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
        var spec_name_map = {};
        //计算出 spec 对应 spec_item.name 
        $.each(spec_key_names, function (index, item) {
            var spec_item = spec_items[spec_keys[index]];
            //记录 spec_id = spec_name
            spec_name_map[spec_item['spec_id']] = spec_key_names[index];
        });

        //添加动态列
        $.each(specs, function (index, spec_id) {
            d_tr += '<td>' + spec_name_map[spec_id] + '</td>';
        });
        $(d_tr).prependTo($tr);
        return $tr;
    }

    /**
     * input 数字值检验
     * @returns {void}     
     **/
    function inputNumCheck($dom) {
        $dom.val($dom.val().replace(/[^0-9-\.]+/, ''));
    }

    /**
     * 价格项 input 发生改变
     * @param {type} spec_key
     * @param {type} attrName
     * @param {type} $dom
     * @returns {undefined}     */
    function priceItemChanged(spec_key, attrName, $dom) {
        var itemSpriteVO = spec_item_prices[spec_key];
        itemSpriteVO[attrName] = $dom.val();
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
     * 所有数组的笛卡尔积
     * 
     * @param unknown_type $data
     */
    function combineDika(args)
    {
        var cnt = args.length;
        var result = [];
        for (var i in args[0]) {
            //已选择才会加入计算
            if (spec_items[args[0][i]].selected) {
                result.push([args[0][i]]);
            }
        }
        for (var i = 1; i < cnt; i++) {
            result = combineArray(result, args[i]);
        }
        return result;
    }

    /**
     * 两个数组的笛卡尔积
     * 
     * @param array arr1
     * @param array arr2
     */
    function combineArray(arr1, arr2)
    {
        var result = [];
        for (var i = 0, len = arr1.length; i < len; i++) {
            var arr1_i = arr1[i].concat();
            for (var j = 0, jlen = arr2.length; j < jlen; j++) {
                //已选择才会加入计算
                if (spec_items[arr2[j]].selected) {
                    var temp = arr1_i.concat();
                    temp.push(arr2[j]);
                    result.push(temp);
                }
            }
        }

        return result;
    }

    /**
     * 刷新规格价格显示
     * @returns {void}
     */
    function reflashItemPrice() {
        //必须所有规格都有值
        for (var i = 0, len = specs.length; i < len; i++) {
            if ($('.spec-box[data-id=' + specs[i] + '] .selected').length == 0) {
                $('.spec-price-box .table tbody').empty();
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

    SpecItemVO.prototype.setSelected = function (bo, fire) {
        this.selected = bo;
        if (fire || fire == undefined) {
            $(this).trigger('changed');
        }
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
        this.goods_cost = 0;
        this.goods_price = 0;
        this.scene_num = 0;
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

    SpecItemSpriteVO.prototype.load = function (data) {
        $.extend(this, data);
    }

    SpecItemSpriteVO.prototype.setSelected = function (bo) {
        this.selected = bo;
        $(this).trigger('changed');
    }
</script>

<script>
    //图片上传器
    var imgUploaders = {};
    function createImgUploader(id, value) {
        Wskeee.require(['euploader'], function (euploader) {
            var config = {
                name: 'spec-img-' + id,
                //input value 默认使用的属性
                targetAttribute: 'url',
                //图片默认压缩
                compress: false,
                //组件绝对路径
                sourcePath: '<?= $sourcePath ?>',
                // 文件接收服务端。
                server: '/webuploader/default/upload',
                //检查文件是否存在
                checkFile: '/webuploader/default/check-file',
                //分片合并
                mergeChunks: '/webuploader/default/merge-chunks',
                // 选择文件的按钮。可选。
                auto: true,
                // 上传容器
                container: '#spec-img-' + id,
                //附加样式
                rootAddedClass: 'mini',
                //最大文件数
                fileNumLimit: 1,
                //同时上传多个文件数量
                threads: 2,
                //csrf
                formData: {
                    '<?= Yii::$app->request->csrfParam ?>': '<?= Yii::$app->request->csrfToken ?>'
                },
                accept: {
                    title: 'Images',
                    extensions: 'gif,jpg,jpeg,bmp,png',
                    mimeTypes: 'image/*'
                }
            };

            var uploader = new euploader.Uploader(config, euploader.TileView);
            $(uploader).on('uploadComplete', function (evt, dbfile, file) {
                spec_item_prices[id]['spec_img_url'] = dbfile.url;
            });
            if (value != "") {
                uploader.addCompleteFiles([{
                        'id': "file-picker-" + Math.round(Math.random() * 1000),
                        'thumb_url': value,
                        'url': value,
                        'ext': 'jpg',
                        'size': 0,
                        'name': ''
                    }]);
            }
            imgUploaders[id] = uploader;
        });
    }

    /**
     * 销毁图片上传器
     * @param {string} id 
     * @returns {void}     
     **/
    function destoryImgUploader(id) {
        var uploader = imgUploaders[id];
        if (uploader) {
            $(uploader).off();
            uploader.destroy();
            delete imgUploaders[id];
        }
    }

    /**
     * 销毁所有图片上传器
     * @returns {void}     
     **/
    function destoryAllImgUploader() {
        $.each(imgUploaders, function (i, uploader) {
            destoryImgUploader(i);
        });
    }

</script>
