<?php

namespace backend\modules\statistics\actions;

use common\models\api\ApiResponse;
use common\models\order\Order;
use common\models\platform\DailyVisitTrend;
use Yii;
use yii\base\Action;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * 获取平台用户转化率
 *
 * @author Administrator
 */
class GetPlatformUCR extends Action
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
        //按日订单数
        $orderStatistic = Order::getDailySaleLog($day_num);
        $orderStatistic = ArrayHelper::map($orderStatistic, 'date', 'count');

        $datas = [];
        foreach ($dailyStatistic as $date => $count) {
            $orderCount = isset($orderStatistic[$date]) ? $orderStatistic[$date] : 0;
            $datas[] = [
                'name' => $date,
                'value' => [$date, $count == 0 ? 0 : round($orderCount / $count * 100)]
            ];
        }

        return new ApiResponse(ApiResponse::CODE_COMMON_OK, null, $datas);
    }

}
