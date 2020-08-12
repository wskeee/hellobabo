<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\goods\GoodsScene;
use common\models\goods\SceneGroup;
use common\models\order\OrderGoods;
use common\models\order\OrderGoodsScene;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * 获取订单商品详情
 *
 * @author Administrator
 */
class GetOrderGoodsDetail extends BaseAction
{
    /* 必须参数 */

    protected $requiredParams = ['order_goods_id'];

    public function run()
    {
        $order_goods_id = $this->getSecretParam('order_goods_id');
        $model = OrderGoods::findOne(['id' => $order_goods_id]);

        if ($model == null) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => Yii::t('app', 'Order')]);
        }

        return new Response(Response::CODE_COMMON_OK, null, [
            'order_goods' => $model,
            'goods' => $model->goods,
        ]);
    }

}
