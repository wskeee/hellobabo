<?php

namespace backend\modules\order_admin\assets;

use yii\web\AssetBundle;
use const YII_DEBUG;

/**
 * Main backend application asset bundle.
 */
class OrderModuleAsset extends AssetBundle
{
    public $sourcePath = '@backend/modules/order_admin/assets';
    public $baseUrl = '@backend/modules/order_admin/assets';
    public $css = [
        'css/order_module.css',
    ];
    public $js = [
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
