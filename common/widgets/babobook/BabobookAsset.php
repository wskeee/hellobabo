<?php

namespace common\widgets\babobook;

use yii\web\AssetBundle;
use yii\web\View;

class BabobookAsset extends AssetBundle
{
    /* v1 */
    public $css = [
        'css/style.css',
    ];
    public $js = [
        'vendor/createjs.min.js',
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
