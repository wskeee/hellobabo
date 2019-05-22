<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\goods\Goods;
use common\models\goods\GoodsSpecPrice;
use common\models\UserAddress;
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
        $goods_id = $this->getSecretParam('goods_id');
        $goods = Goods::findOne(['id' => $goods_id]);
        if ($goods == null) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => Yii::t('app', 'Goods')]);
        }

        $address = UserAddress::findOne(['user_id' => Yii::$app->user->id, 'is_default' => 1]);
        $gsp = GoodsSpecPrice::findOne(['goods_id' => $goods_id, 'is_del' => 0]);

        return new Response(Response::CODE_COMMON_OK, null, [
            'goods' => $goods,
            'specs' => $goods->goodsModel->goodsSpecs, //规格
            'spec_items' => $goods->getGoodsSpecItems(), //规格项
            'gsp' => $gsp,
            'address' => $address->getDetail(),
        ]);
    }

}
