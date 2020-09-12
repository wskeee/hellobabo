<?php


namespace apiend\modules\v1\controllers;


use apiend\controllers\ApiController;
use apiend\modules\v1\actions\shop\GetIncomeList;
use apiend\modules\v1\actions\shop\GetIncomeStat;
use apiend\modules\v1\actions\shop\GetRank;
use apiend\modules\v1\actions\shop\GetShopStat;

/**
 * 商家API
 * Class ShopController
 * @package apiend\modules\v1\controllers
 */
class ShopController extends ApiController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['optional'] = [
            'get-shop-stat',
            'get-income-list',
            'get-income-stat',
            'get-rank',
        ];
        $behaviors['verbs']['actions'] = [
            'get-shop-stat' => ['get'],
            'get-income-list' => ['get'],
            'get-income-stat' => ['get'],
            'get-rank' => ['get'],
        ];
        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'get-shop-stat' => ['class' => GetShopStat::class],
            'get-income-list' => ['class' => GetIncomeList::class],
            'get-income-stat' => ['class' => GetIncomeStat::class],
            'get-rank' => ['class' => GetRank::class],
        ];
    }
}