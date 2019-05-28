<?php

namespace apiend\modules\v1\controllers;

use apiend\controllers\ApiController;
use apiend\modules\v1\actions\goods\GetGoodsDetail;
use apiend\modules\v1\actions\goods\GetGoodsList;
use apiend\modules\v1\actions\goods\GetMaterialList;

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
            
        ];
        $behaviors['verbs']['actions'] = [
            'get-list' => ['get'],
            'get-detail' => ['get'],
            'get-material-list' => ['get'],
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
            'get-material-list' => ['class' => GetMaterialList::class],
        ];
    }

}
