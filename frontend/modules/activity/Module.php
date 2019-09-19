<?php

namespace frontend\modules\activity;

/**
 * activity module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'frontend\modules\activity\controllers';
    
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
