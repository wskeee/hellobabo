<?php

namespace backend\modules\statistics\actions;

use common\models\api\ApiResponse;
use common\models\order\Order;
use Yii;
use yii\base\Action;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * 获取平台设备使用率
 *
 * @author Administrator
 */
class GetPlatformGoodsUsage extends Action
{

    /**
     * @param int $day_num 最近天数
     */
    public function run($day_num = 7)
    {
        Yii::$app->response->format = 'json';

        $result = Order::getSaleStatByGoods($day_num);
        $key_names = array_unique(array_column($result, 'date'));
        $result = ArrayHelper::index($result, null, 'goods_id');
        $zero_value = [];

        $datas = [];
        foreach ($result as $data) {
            $date_to_count = ArrayHelper::map($data,'date','count');
            $value = [];
            foreach ($key_names as $date) {
                $value[] = [$date, isset($date_to_count[$date]) ? $date_to_count[$date] : 0];
            }
            $datas[] = [
                'goods_id' => $data[0]['goods_id'],
                'goods_name' => $data[0]['goods_name'],
                'value' => $value
            ];
        }
        return new ApiResponse(ApiResponse::CODE_COMMON_OK, null, ['keys' => $key_names, 'data' => $datas]);
    }

}
