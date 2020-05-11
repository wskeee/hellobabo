<?php

namespace frontend\modules\wechat\controllers;

use Yii;
use yii\web\Controller;

/**
 * Default controller for the `wechat` module
 */
class DefaultController extends Controller
{
    public function actionTest()
    {
        if (Yii::$app->wechat->isWechat && !Yii::$app->wechat->isAuthorized()) {
            return Yii::$app->wechat->authorizeRequired()->send();
        }else{
            var_dump('授权成功！',Yii::$app->wechat->user);
        }
    }
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
