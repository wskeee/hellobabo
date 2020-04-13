<?php

namespace common\components\aip_php_sdk;

include_once 'AipBodyAnalysis.php';

class BaiduApi
{
    private static $_client;

    public static function getClient()
    {
        if (!self::$_client) {
            $config = \Yii::$app->params['baidu']['apiBody'];
            self::$_client = new \AipBodyAnalysis($config['APP_ID'], $config['API_KEY'], $config['SECRET_KEY']);
        }
        return self::$_client;
    }
}