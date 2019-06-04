<?php

namespace apiend\modules\v1\controllers;

use apiend\controllers\ApiController;
use apiend\modules\v1\actions\platform\AddIssue;

/**
 * 平台接口
 *
 * @author Administrator
 */
class PlatformController extends ApiController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['optional'] = [
            
        ];
        $behaviors['verbs']['actions'] = [
            'add-issue' => ['post'],
        ];
        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'add-issue' => ['class' => AddIssue::class],
        ];
    }

}
