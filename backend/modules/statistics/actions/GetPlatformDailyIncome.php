<?php

namespace backend\modules\statistics\actions;

use common\models\api\ApiResponse;
use common\models\order\Order;
use common\models\platform\PlatformSaleLog;
use Yii;
use yii\base\Action;
use yii\helpers\ArrayHelper;

/**
 * 获取平台每日收益
 *
 * @author Administrator
 */
class GetPlatformDailyIncome extends Action
{
    /**
     * @param int $day_num 最近天数
     */
    public function run($day_num = 7)
    {
        Yii::$app->response->format = 'json';
        $daily_sale_logs = Order::getDailySaleLog($day_num);

        //生成销售记录，没有补0
        $daily_sale_logs = ArrayHelper::map($daily_sale_logs, 'date', 'real_income');
        $daily_incomes = [];
        for ($i = $day_num; $i > 0; $i--) {
            $date = date('Y-m-d', strtotime("today -$i day"));
            $daily_incomes[] = [
                'name' => $date,
                'value' => [$date, isset($daily_sale_logs[$date]) ? $daily_sale_logs[$date] : 0],
            ];
        }
        return new ApiResponse(ApiResponse::CODE_COMMON_OK, null, $daily_incomes);
    }

}
