<?php

namespace frontend\modules\show;

/**
 * show module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'frontend\modules\show\controllers';
    
    public $layout = '@frontend/views/layouts/main_empty';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
