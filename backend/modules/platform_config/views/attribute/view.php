<?php

use common\models\goods\GoodsAttribute;
use common\utils\I18NUitl;
use yii\helpers\Html;
use yii\web\View;
use yii\web\YiiAsset;

/* @var $this View */
/* @var $model GoodsAttribute */

$this->title = I18NUitl::t('app', "{Goods}{Attribute}：{Name}", ['Name' => $model->name]);
$this->params['breadcrumbs'][] = ['label' => I18NUitl::t('app', '{Model}:{Name}', ['Name' => $model->goodsModel->name]), 'url' => ['/platform_config/goods-model/view', 'id' => $model->model_id]];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="goods-attribute-view">
    <!--属性值-->
    <div class="wsk-panel pull-left">

        <div class="title">
            <div class="pull-left">
                <?= I18NUitl::t('app', '{Attribute}{Value}') ?>
            </div>

            <div class="btngroup pull-right">
                <span class="loading" style="display: none;"></span>
                <?= Html::a(Yii::t('app', 'Add'), ['attribute-value/create', 'attribute_id' => $model->id], ['id' => 'btn-addValue', 'class' => 'btn btn-primary btn-flat']);?>
            </div>
        </div>

        <?=
        $this->render('/attribute-value/index', [
            'dataProvider' => $dataProvider,
        ])
        ?>

    </div>    

</div>
<?php
$js = <<<JS
    
    // 弹出商品属性值面板
    $('#btn-addValue').click(function(e){
        e.preventDefault();
        var _this = this;
        $('.loading').show();
        $(_this).addClass('disabled');
        $.get(this.href,function(r){
            if(r.code == '0'){
                location.reload();
            }else{
                alert(r.msg);
                $('.loading').hide();
                $(_this).removeClass('disabled');
            }
        });
    });
    
JS;
$this->registerJs($js, View::POS_READY);
?>