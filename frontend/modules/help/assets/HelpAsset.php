<?php

namespace frontend\modules\help\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class HelpAsset extends AssetBundle
{
    public $sourcePath = '@frontend/modules/help/assets';
    //public $basePath = '@webroot';
    //public $baseUrl = '@web';
    public $css = [
        'css/help.css',
    ];
    public $js = [
        
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];
}
