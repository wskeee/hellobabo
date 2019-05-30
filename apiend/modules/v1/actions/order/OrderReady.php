<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\goods\Goods;
use common\models\goods\GoodsSpecItem;
use common\models\UserAddress;
use Yii;
use yii\helpers\ArrayHelper;

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
        $specs = $goods->goodsModel->goodsSpecs;
        $gsps = $goods->getGoodsSpecPrices();
        $gsp_key = $gsps[0]['spec_key'];
        $gsps_items_ids = array_filter(explode('_', implode('_', ArrayHelper::getColumn($gsps, 'spec_key'))));
        $spec_items = ArrayHelper::index(GoodsSpecItem::find()->where(['id' => $gsps_items_ids])->asArray()->all(), 'id');

        //计算库存
        $gsps_spec_key_store = ArrayHelper::map($gsps, 'spec_key', 'store_count');
        $spec_store = [];
        foreach ($gsps_spec_key_store as $spec_key => $store_count) {
            //每个规格项中每个规格库存
            $spec_ids = explode('_', $spec_key);
            foreach ($spec_ids as $spec_id) {
                $spec_item = &$spec_items[$spec_id];
                if (!isset($spec_item['store_count'])) {
                    $spec_item['store_count'] = 0;
                }
                $spec_item['store_count'] += $store_count;
            }
        }

        $spec_items = ArrayHelper::index($spec_items, null, 'spec_id');
        foreach ($specs as &$spec) {
            $spec = $spec->toArray();
            $spec['items'] = $spec_items[$spec['id']];
        }



        return new Response(Response::CODE_COMMON_OK, null, [
            'goods' => $goods,
            'specs' => $specs, //规格
            'gsps' => ArrayHelper::index($gsps, 'spec_key'), //规格价格
            'gsp_key' => $gsp_key, //默认规格
            'address' => $address->getDetail(),
        ]);
    }

}