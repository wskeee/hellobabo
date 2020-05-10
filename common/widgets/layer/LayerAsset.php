<?php

namespace common\widgets\layer;

use yii\web\AssetBundle;
use yii\web\View;

class LayerAsset extends AssetBundle
{
    /* v3 */
    public $css = [

    ];
    public $js = [
        'layer.js',
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
