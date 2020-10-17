<?php

namespace apiend\modules\v1\controllers;

use apiend\controllers\ApiController;
use apiend\modules\v1\actions\platform\AddComment;
use apiend\modules\v1\actions\platform\AddIssue;
use apiend\modules\v1\actions\platform\GetCommentList;
use apiend\modules\v1\actions\platform\GetHomeConfig;

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
            'get-comment-list'
        ];
        $behaviors['verbs']['actions'] = [
            'add-issue' => ['post'],
            'add-comment' => ['post'],
            'get-comment-list' => ['get'],
            'get-home-config' => ['get'],
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
            'add-comment' => ['class' => AddComment::class],
            'get-comment-list' => ['class' => GetCommentList::class],
            'get-home-config' => ['class' => GetHomeConfig::class],
        ];
    }

}
