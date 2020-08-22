<?php

namespace apiend\modules\v1\controllers;

use apiend\controllers\ApiController;
use apiend\modules\v1\actions\goods\GetCategoryDetail;
use apiend\modules\v1\actions\goods\GetGoodsCategoryList;
use apiend\modules\v1\actions\goods\GetGoodsDetail;
use apiend\modules\v1\actions\goods\GetGoodsList;
use apiend\modules\v1\actions\goods\GetGoodsMaterialValueList;
use apiend\modules\v1\actions\goods\GetGoodsTagList;
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
            'get-goods-material-value-list',
            'get-goods-tag-list',
            'get-category-list',
            'get-category-detail',
        ];
        $behaviors['verbs']['actions'] = [
            'get-list' => ['get'],
            'get-detail' => ['get'],
            'get-material-value-list' => ['get'],
            'get-goods-material-value-list' => ['get'],
            'get-goods-tag-list' => ['get'],
            'get-category-list' => ['get'],
            'get-category-detail' => ['get'],
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
            'get-goods-material-value-list' => ['class' => GetGoodsMaterialValueList::class],
            'get-goods-tag-list' => ['class' => GetGoodsTagList::class],
            'get-category-list' => ['class' => GetGoodsCategoryList::class],
            'get-category-detail' => ['class' => GetCategoryDetail::class],
        ];
    }

}
