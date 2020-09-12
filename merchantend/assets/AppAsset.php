<?php

namespace merchant\assets;

use yii\web\AssetBundle;

/**
 * Main merchant application asset bundle.
 */
class AppAsset extends AssetBundle
{
//    public $basePath = '@webroot';
//    public $baseUrl = '@web';
    public $sourcePath = '@merchant/assets';
    
    public $css = [
        'css/base.css',
        'css/common.css',
    ];
    public $js = [
        'js/wskeee.stringutils.js'  //渲染
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];
}
