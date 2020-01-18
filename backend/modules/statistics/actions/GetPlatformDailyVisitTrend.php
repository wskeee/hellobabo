<?php

namespace backend\modules\statistics\actions;

use common\models\api\ApiResponse;
use common\models\platform\DailyVisitTrend;
use Yii;
use yii\base\Action;
use yii\helpers\ArrayHelper;

/**
 * 获取平台用户日趋势
 *
 * @author Administrator
 */
class GetPlatformDailyVisitTrend extends Action
{

    /**
     * @param int $day_num 最近天数
     */
    public function run($day_num = 7)
    {
        Yii::$app->response->format = 'json';
        //访问趋势
        $dailyStatistic = DailyVisitTrend::getVisitTrend($day_num);
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
