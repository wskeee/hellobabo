<?php

namespace apiend\modules\v1\controllers;

use apiend\controllers\ApiController;
use apiend\modules\v1\actions\help\GetList;

/**
 * 问题
 *
 * @author Administrator
 */
class HelpController extends ApiController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['optional'] = [
        ];
        $behaviors['verbs']['actions'] = [
            'get-list' => ['get'],
        ];
        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'get-list' => ['class' => GetList::class],
        ];
    }

}
