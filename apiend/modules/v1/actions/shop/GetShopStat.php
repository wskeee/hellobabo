<?php


namespace apiend\modules\v1\actions\shop;


use apiend\models\Response;
use common\models\goods\Goods;
use common\models\shop\Shop;
use yii\base\Action;

class GetShopStat extends Action
{
    public function run()
    {
        $shop_id = \Yii::$app->request->getQueryParam('shop_id', 0);
        $shop = Shop::findOne(['id' => $shop_id])->toArray();
        if (!$shop) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, '找不到数据！');
        }
        $goods_count = Goods::find()->where(['shop_id' => $shop_id])->count();
        $shop['goods_count'] = $goods_count;
        return new Response(Response::CODE_COMMON_OK, null, $shop);
    }
}