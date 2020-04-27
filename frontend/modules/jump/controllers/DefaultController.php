<?php

namespace app\modules\jump\controllers;

use yii\web\Controller;

/**
 * Default controller for the `jump` module
 */
class DefaultController extends Controller
{
    public function actionWxMin()
    {
        return '请在微信小程序打开！';
    }
}
