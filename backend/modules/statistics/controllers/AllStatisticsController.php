<?php

namespace backend\modules\statistics\controllers;

use backend\modules\statistics\actions\GetPlatformDailyIncome;
use backend\modules\statistics\actions\GetPlatformDailyVisitTrend;
use backend\modules\statistics\actions\GetPlatformGoodsUsage;
use backend\modules\statistics\actions\GetPlatformInstanceStatistic;
use backend\modules\statistics\actions\GetPlatformMonthlyVisitTrend;
use backend\modules\statistics\actions\GetPlatformThisDayMonthSale;
use backend\modules\statistics\actions\GetPlatformUCR;
use backend\modules\statistics\actions\GetPlatformWorkflowStat;
use yii\db\Query;
use yii\web\Controller;

/**
 * Default controller for the `statistic_admin` module
 */
class AllStatisticsController extends Controller
{
    public function actions()
    {
        return [
            'get-platform-instance-statistic' => GetPlatformInstanceStatistic::class,
            'get-platform-this-day-month-sale' => GetPlatformThisDayMonthSale::class,
            'get-platform-workflow-stat' => GetPlatformWorkflowStat::class,
            'get-platform-daily-income' => GetPlatformDailyIncome::class,
            'get-platform-goods-usage' => GetPlatformGoodsUsage::class,
            'get-platform-ucr' => GetPlatformUCR::class,
            'get-platform-daily-vt' => GetPlatformDailyVisitTrend::class,
            'get-platform-month-vt' => GetPlatformMonthlyVisitTrend::class,
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('admin', [

        ]);
    }
}
