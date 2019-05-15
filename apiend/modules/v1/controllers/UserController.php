<?php

namespace apiend\modules\v1\controllers;

use apiend\controllers\ApiController;
use apiend\modules\v1\actions\user\BindInfo;
use apiend\modules\v1\actions\user\GetInfo;

/**
 * 登录认证
 *
 * @author Administrator
 */
class UserController extends ApiController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['optional'] = [
            
        ];
        $behaviors['verbs']['actions'] = [
            'get-info' => ['get'],
            'bind-info' => ['post'],
        ];
        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'get-info' => ['class' => GetInfo::class],
            'bind-info' => ['class' => BindInfo::class],
        ];
    }

}
