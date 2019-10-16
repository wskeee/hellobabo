<?php

namespace apiend\modules\v1\controllers;

use apiend\controllers\ApiController;
use apiend\modules\v1\actions\goods\GetGoodsDetail;
use apiend\modules\v1\actions\goods\GetGoodsList;
use apiend\modules\v1\actions\goods\GetMaterialValueList;

/**
 * 绘本
 *
 * @author Administrator
 */
class GoodsController extends ApiController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['optional'] = [
            'get-list',
            'get-detail',
            'get-material-value-list',
        ];
        $behaviors['verbs']['actions'] = [
            'get-list' => ['get'],
            'get-detail' => ['get'],
            'get-material-value-list' => ['get'],
        ];
        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'get-list' => ['class' => GetGoodsList::class],
            'get-detail' => ['class' => GetGoodsDetail::class],
            'get-material-value-list' => ['class' => GetMaterialValueList::class],
        ];
    }

}
