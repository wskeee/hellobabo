<?php

namespace backend\modules\statistics\actions;

use common\models\api\ApiResponse;
use common\models\order\Order;
use common\models\order\WorkflowDelivery;
use common\models\order\WorkflowDesign;
use common\models\order\WorkflowPrint;
use common\models\platform\PlatformSaleLog;
use Yii;
use yii\base\Action;

/**
 * 获取工作流统计工作
 *
 * @author Administrator
 */
class GetPlatformWorkflowStat extends Action
{

    public function run()
    {
        Yii::$app->response->format = 'json';
        // 今天、本月 开始与结束时间
        $today_start_time = strtotime(date('Ymd').' 00:00:00');
        $today_end_time = strtotime(date('Ymd').' 00:00:00 +1 day');
        $month_start_time = strtotime('first day of this month 00:00:00');
        $month_end_time = strtotime('last day of this month 23:59:59');

        $today_design_stat = WorkflowDesign::getStat($today_start_time, $today_end_time);
        $month_design_stat = WorkflowDesign::getStat($month_start_time, $month_end_time);
        $today_print_stat = WorkflowPrint::getStat($today_start_time, $today_end_time);
        $month_print_stat = WorkflowPrint::getStat($month_start_time, $month_end_time);
        $today_delivery_stat = WorkflowDelivery::getStat($today_start_time, $today_end_time);
        $month_delivery_stat = WorkflowDelivery::getStat($month_start_time, $month_end_time);

        $datas = [
            [
                'flow_name' => Yii::t('app','Design'),
                'today_stat' => $today_design_stat,
                'month_stat' => $month_design_stat,
            ],
            [
                'flow_name' => Yii::t('app','Print'),
                'today_stat' => $today_print_stat,
                'month_stat' => $month_print_stat,
            ],
            [
                'flow_name' => Yii::t('app','Delivery'),
                'today_stat' => $today_delivery_stat,
                'month_stat' => $month_delivery_stat,
            ],
        ];

        return new ApiResponse(ApiResponse::CODE_COMMON_OK, null, $datas);
    }

}
