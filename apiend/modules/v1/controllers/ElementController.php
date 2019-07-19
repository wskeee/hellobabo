<?php

namespace apiend\modules\v1\controllers;

use apiend\controllers\ApiController;
use apiend\modules\v1\actions\element\GetElementList;

/**
 * 元素
 *
 * @author Administrator
 */
class ElementController extends ApiController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['optional'] = [
            'get-list'
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
            'get-list' => ['class' => GetElementList::class],
        ];
    }

}
