<?php

namespace common\widgets\viewer;

use yii\web\AssetBundle;
use yii\web\View;

class ViewerAsset extends AssetBundle
{
    /* v3 */
    public $css = [
        'assets/viewer.min.css',
    ];
    public $js = [
        'assets/viewer.min.js',
        'assets/jquery-viewer.min.js',
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
