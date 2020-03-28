<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\components\redis\RedisService;
use common\models\goods\Goods;
use common\models\order\Order;
use common\models\order\OrderAction;
use phpDocumentor\Reflection\Types\Self_;
use Yii;

/**
 * 尝试下单
 *
 * @author Administrator
 */
class SaveTempOrder extends BaseAction
{
    const REDIS_KEY = 'order_try_';

    protected $requiredParams = ['data'];

    public function run()
    {
        $data = json_decode($this->getSecretParam('data'), true);
        $goods_id = $data['goods_id'];

        if (empty($goods_id)) {
            return new Response(Response::CODE_ORDER_SAVE_TRY_FAILED, '找不到商品数据！');
        }

        if (($goods = Goods::find()->where(['goods_id' => $goods_id, 'is_del' => 0])) == null) {
            return new Response(Response::CODE_ORDER_SAVE_TRY_FAILED, '找不到商品数据！');
        }

        $user_id = Yii::$app->user->id;
        $key = self::REDIS_KEY . "{$user_id}_{$goods_id}";
        // 数据校检
        $data['updated_at'] = time();
        $data['sign'] = md5($data['updated_at']);
        $result = RedisService::getRedis()->set($key, json_encode($data));
        if ($result) {
            return new Response(Response::CODE_COMMON_OK);
        } else {
            return new Response(Response::CODE_ORDER_SAVE_TRY_FAILED);
        }
    }



}
