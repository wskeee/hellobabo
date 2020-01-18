<?php

namespace backend\modules\statistics\actions;

use common\models\api\ApiResponse;
use common\models\goods\Goods;
use common\models\order\Order;
use common\models\order\OrderRecommend;
use common\models\User;
use Yii;
use yii\base\Action;

/**
 * 平台实例汇总
 */
class GetPlatformInstanceStatistic extends Action
{

    /**
     * @param string $date  2019 | 201902
     */
    public function run($date = null)
    {
        Yii::$app->response->format = 'json';

        //总单数
        $order_count_query = Order::find()->where(['order_status' => Order::ORDER_STATUS_WAIT_DELIVER]);
        //总营业
        $turnover_query = Order::find()->select(['sum(order_amount)'])->where(['order_status' => Order::ORDER_STATUS_WAIT_DELIVER]);
        //设备数
        $goods_count_query = Goods::find()->where(['status' => Goods::STATUS_PUBLISHED]);
        //客户
        $client_count_query = User::find();

        if (!empty($date)) {
            $is_year = strlen($date) == 4;
            $start_time = strtotime("first day of $date".($is_year ? '-01' : ''));
            $end_time = strtotime("last day of $date".($is_year ? '-12' : ''));
            $order_count_query->andWhere(['between', 'created_at', $start_time, $end_time]);
            $turnover_query->andWhere(['between', 'created_at', $start_time, $end_time]);
            $goods_count_query->andWhere(['between', 'created_at', $start_time, $end_time]);
            $client_count_query->andWhere(['between', 'created_at', $start_time, $end_time]);
        }
        // 推广费
        $promotion_expenses = OrderRecommend::getPromotionExpensesStat(!empty($date) ? ['between', 'created_at', $start_time, $end_time] : null);

        return new ApiResponse(ApiResponse::CODE_COMMON_OK, null, [
            'sale_count' => $order_count_query->count(),
            'turnover' => round($turnover_query->column()[0]*100)/100,
            'goods_count' => $goods_count_query->count(),
            'client_count' => $client_count_query->count(),
            'promotion_expenses' => $promotion_expenses,
        ]);
    }

}
