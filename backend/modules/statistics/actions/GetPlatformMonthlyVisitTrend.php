<?php

namespace backend\modules\statistics\actions;

use common\models\api\ApiResponse;
use common\models\platform\DailyVisitTrend;
use Yii;
use yii\base\Action;
use yii\helpers\ArrayHelper;

/**
 * 获取平台用户月趋势
 *
 * @author Administrator
 */
class GetPlatformMonthlyVisitTrend extends Action
{

    /**
     * @param int $year 最近天数
     */
    public function run($year)
    {
        if (empty($year)) {
            $year = date('Y');
        }
        
        Yii::$app->response->format = 'json';
        //访问趋势
        $dailyStatistic = DailyVisitTrend::find()
                ->select(['ref_date date', 'session_cnt count'])
                ->where(['between', 'date', date('Y-m-d', strtotime("first day of $year")), date('Y-m-d', strtotime("last day of $year"))])
                ->asArray()
                ->all();
        $dailyStatistic = ArrayHelper::map($dailyStatistic, 'date', 'count');

        $datas = [];
        foreach ($dailyStatistic as $date => $count) {
            $datas[] = [
                'name' => $date,
                'value' => [$date, $count]
            ];
        }

        return new ApiResponse(ApiResponse::CODE_COMMON_OK, null, $datas);
    }

}
