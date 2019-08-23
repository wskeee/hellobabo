<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\order\OrderGoods;
use Yii;

/**
 * 获取我的绘本
 */
class GetMyOrderGoods extends BaseAction
{

    public function run()
    {
        $user = Yii::$app->user->identity;
        $orderGoods = OrderGoods::find()
                ->where(['created_by' => $user->id, 'is_del' => 0])
                ->andWhere(['<>', 'status', OrderGoods::STATUS_UNREADY])
                ->andWhere(['<>', 'status', OrderGoods::STATUS_INVALID])
                ->all();
        return new Response(Response::CODE_COMMON_OK, null, [
            'list' => $orderGoods
        ]);
    }

}
