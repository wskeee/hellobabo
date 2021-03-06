<?php

namespace common\widgets\babobook;

use yii\web\AssetBundle;
use yii\web\View;

class BabobookAsset extends AssetBundle
{
    /* v3 */
    public $css = [
        'css/style.css',
    ];
    public $js = [
        'vendor/createjs.min.js',
        'jweixin-1.3.2.js',
        'app.bundle.js',
        'common/common.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset',
    ];
    
    public $jsOptions = [
        'position' => View::POS_HEAD
    ];
    
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = __DIR__;
        parent::init();
    }
}
