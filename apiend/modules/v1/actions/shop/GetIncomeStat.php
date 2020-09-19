<?php


namespace apiend\modules\v1\actions\shop;


use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\services\ShopService;
use Yii;
use yii\base\Action;
use yii\helpers\ArrayHelper;

/**
 * 月收益统计
 * Class GetMonthIncomeStat
 * @package apiend\modules\v1\actions\shop
 */
class GetIncomeStat extends Action
{
    public function run()
    {
        $shop_id = Yii::$app->request->getQueryParam('shop_id');
        $year = Yii::$app->request->getQueryParam('year', '');
        $month = Yii::$app->request->getQueryParam('month', '');

        $start_time = $end_time = null;
        $is_year = false;
        if ($year && $month) {
            $start_time = strtotime("first day of $year-$month");
            $end_time = strtotime("last day of $year-$month 23:59:59");
            $res = ShopService::getIncomeStat(['shop_id' => $shop_id], $start_time, $end_time, $is_year);
            //生成销售记录，没有补0
            $day_num = date('t', time());
            $daily_sale_logs = ArrayHelper::map($res, 'date', 'value');
            $all_incomes = array_sum(array_column($res, 'value'));
            $incomes = [];
            for ($i = 1; $i <= $day_num; $i++) {
                $d = $i > 9 ? $i : "0$i";
                $date = date('Y-m-d', strtotime("$year-$month-$d"));
                $incomes[] = [
                    'name' => date('d', strtotime("$year-$month-$d")),
                    'value' => isset($daily_sale_logs[$date]) ? $daily_sale_logs[$date] : 0,
                ];
            }
        } else if ($year) {
            $start_time = strtotime("first day of $year-01");
            $end_time = strtotime("last day of $year-12 23:59:59");
            $is_year = true;

            $res = ShopService::getIncomeStat(['shop_id' => $shop_id], $start_time, $end_time, $is_year);
            //生成销售记录，没有补0
            $month_num = 12;
            $daily_sale_logs = ArrayHelper::map($res, 'date', 'value');
            $all_incomes = array_sum(array_column($res, 'value'));
            $all_incomes = round($all_incomes * 100) / 100;
            $incomes = [];
            for ($i = 1; $i <= $month_num; $i++) {
                $d = $i > 9 ? $i : "0$i";
                $date = date('Y-m', strtotime("$year-$d"));
                $incomes[] = [
                    'name' => "{$i}月",
                    'value' => isset($daily_sale_logs[$date]) ? $daily_sale_logs[$date] : 0,
                ];
            }
        }


        return new Response(Response::CODE_COMMON_OK, null, ['all_incomes' => $all_incomes, 'incomes' => $incomes]);
    }
}