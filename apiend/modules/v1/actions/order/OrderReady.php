<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\goods\Goods;
use Yii;

/**
 * 下单准备
 */
class OrderReady extends BaseAction
{
    /* 必须参数 */

    protected $requiredParams = ['goods_id'];

    public function run()
    {
        $goods = Goods::findOne(['id' => $this->getSecretParam('goods_id')]);
        if ($goods == null) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => Yii::t('app', 'Goods')]);
        }

        return new Response(Response::CODE_COMMON_OK, null, [
            'goods' => $goods,
            'spec_prices' => $goods->getGoodsSpecPrices(),
        ]);
    }

}
