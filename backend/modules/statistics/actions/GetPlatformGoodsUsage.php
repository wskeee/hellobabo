<?php

namespace backend\modules\statistics\actions;

use common\models\api\ApiResponse;
use common\models\order\Order;
use Yii;
use yii\base\Action;
use yii\db\Expression;

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

        $datas = [];
        foreach ($result as $data) {
            !isset($datas [$data['goods_id']]) && $datas [$data['goods_id']] = [];
            $datas [$data['goods_id']] ['goods_id'] = $data['goods_id'];
            $datas [$data['goods_id']] ['goods_name'] = $data['goods_name'];
            $datas [$data['goods_id']] ['value'] [] = [$data['date'], $data['count']];
        }
        return new ApiResponse(ApiResponse::CODE_COMMON_OK, null, ['keys' => $key_names, 'data' => array_values($datas)]);
    }

}
