<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\components\redis\RedisService;
use Yii;

/**
 * 获取临时订单
 *
 * @author Administrator
 */
class GetTempOrder extends BaseAction
{
    protected $requiredParams = ['goods_id'];

    public function run()
    {
        $goods_id = $this->getSecretParam('goods_id');
        $user_id = Yii::$app->user->id;
        $result = self::getTempOrder($user_id, $goods_id);

        return new Response(Response::CODE_COMMON_OK, null, $result);
    }


    /**
     * 获取临时订单
     *
     * @param int $user_id 用户ID
     * @param int $goods_id 订单ID
     * @return array|string
     */
    public static function getTempOrder($user_id, $goods_id)
    {
        $key = SaveTempOrder::REDIS_KEY . "{$user_id}_{$goods_id}";
        $result = RedisService::getRedis()->get($key);
        return $result ? json_decode($result, true) : "";
    }

    /**
     * 清除临时订单
     *
     * @param int $user_id 用户ID
     * @param int $goods_id 订单ID
     * @return int
     */
    public static function clearTempOrder($user_id, $goods_id)
    {
        $key = SaveTempOrder::REDIS_KEY . "{$user_id}_{$goods_id}";
        return RedisService::getRedis()->del($key);
    }

}
