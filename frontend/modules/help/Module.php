<?php

namespace frontend\modules\help;

/**
 * help module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'frontend\modules\help\controllers';
    public $layout = '@app/views/layouts/main_empty';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
