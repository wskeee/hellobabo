<?php

namespace apiend\modules\v1\controllers;

use apiend\controllers\ApiController;
use apiend\modules\v1\actions\system\GetRegion;
use apiend\modules\v1\actions\system\Poster;
use apiend\modules\v1\actions\system\TestUpload;
use apiend\modules\v1\actions\system\Upload;

/**
 * 系统接口
 *
 * @author Administrator
 */
class SystemController extends ApiController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['optional'] = [
            'upload-file',
            'poster',
        ];
        $behaviors['verbs']['actions'] = [
            'get-region' => ['get'],
            'upload-file' => ['post'],
            'poster' => ['post','get'],
        ];
        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'get-region' => ['class' => GetRegion::class],
            'upload-file' => ['class' => Upload::class],
            'poster' => ['class' => Poster::class],
        ];
    }

}
