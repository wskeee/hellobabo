<?php

namespace backend\modules\goods_admin\assets;

use yii\web\AssetBundle;
use const YII_DEBUG;

/**
 * Main backend application asset bundle.
 */
class GoodsModuleAsset extends AssetBundle
{
    public $sourcePath = '@backend/modules/goods_admin/assets';
    public $baseUrl = '@backend/modules/goods_admin/assets';
    public $css = [
        'css/goods_module.css',
    ];
    public $js = [
        'js/bootstrap-paginator.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'rmrevin\yii\fontawesome\AssetBundle',
        'yii\bootstrap\BootstrapAsset',
    ];
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];
}
