<?php


namespace frontend\modules\wechat\controllers;


use Yii;
use yii\web\Controller;

class CallbackController extends Controller
{
    public function actionOauthCallback()
    {
        $app = Yii::$app->wechat->app;
        $oauth = $app->oauth;

        // 获取 OAuth 授权结果用户信息
        $user = $oauth->user();
        var_dump(Yii::$app->wechat->isAuthorized());
        if (Yii::$app->wechat->isWechat && !Yii::$app->wechat->isAuthorized()) {
            var_dump(11111);exit;
            Yii::$app->wechat->authorize($user);
        }
        $response = $app->server->serve();
        $app->server->push(function ($message) {
            switch ($message['MsgType']) {
                case 'event':
                    return '收到事件消息';
                    break;
                case 'text':
                    return '收到文字消息';
                    break;
                case 'image':
                    return '收到图片消息';
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                case 'file':
                    return '收到文件消息';
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }

            // ...
        });
        // 将响应输出
        return $response->send();
    }
}