<?php

namespace backend\modules\statistics\actions;

use common\models\api\ApiResponse;
use common\models\order\Order;
use common\models\platform\PlatformSaleLog;
use Yii;
use yii\base\Action;

/**
 * 获取平台本月今日收益
 *
 * @author Administrator
 */
class GetPlatformThisDayMonthSale extends Action
{

    /**
     * @param int $day_num 最近天数
     */
    public function run()
    {
        Yii::$app->response->format = 'json';
        $month_sale_stat = Order::getSaleStat( strtotime("first day of " . date('Ym')),strtotime('last day of ' . date('Ym')));
        $tody_sale_stat = Order::getSaleStat( strtotime(date('Ymd').'00:00:00'),strtotime('+1 day '.date('Ymd').'00:00:00'));

        $datas = [
            'today_sale' => $tody_sale_stat,
            'month_sale' => $month_sale_stat,
        ];

        return new ApiResponse(ApiResponse::CODE_COMMON_OK, null, $datas);
    }

}
