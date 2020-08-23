<?php


namespace common\services;


use common\models\order\Order;
use common\models\order\OrderGoods;
use common\models\shop\Shop;
use common\models\shop\ShopSaleRecord;
use yii\helpers\ArrayHelper;

class ShopService
{
    use BaseServiceTrait;

    /**
     * 添加销售记录
     * @param int $order_id
     */
    public static function addSaleRecord($order_id)
    {
        /** @var Order $order */
        $order = Order::find()
            ->where(['id' => $order_id, 'order_status' => Order::ORDER_STATUS_WAIT_DELIVER])
            ->one();
        if (!$order) {
            return self::fail([], '找不到订单数据');
        }

        $shop = $order->shop;

        // 计算商家实际收益 = 订单总额 * 分成比例(income_value < 1) 或者 直接分成(income_value > 1)
        $real_income = $shop->income_value > 1 ? $shop->income_value : $order->goods_amount * $shop->income_value;
        $rows = [
            $shop->id,
            $order->id,
            $shop->income_value,
            $real_income
        ];

        // 生成销售记录
        $shopSaleRecord = new ShopSaleRecord($rows);
        $tran = \Yii::$app->db->beginTransaction();
        try {
            // 增加商家销售计数
            Shop::updateAllCounters(['order_count' => 1]);
            // 保存商家销售记录
            if ($shopSaleRecord->save()) {
                $tran->commit();
                return self::success();
            } else {
                throw new \Exception(implode(',', $shopSaleRecord->getErrorSummary(true)));
            }
        } catch (\Exception $e) {
            $tran->rollBack();
            return self::fail([], $e->getMessage());
        }
    }

    /**
     * 通过用户ID查询商家
     * @param int $uid
     */
    public static function findShopByUserId($uid)
    {
        return Shop::find()->where(['user_id' => $uid, 'status' => Shop::STATUS_ENABLED])->one();
    }

    /**
     * 获取可用商家列表
     * @param bool $map 是否返回 map 格式
     * @return array
     */
    public static function getUserableList($map = true)
    {
        $result = Shop::find()->where(['status' => Shop::STATUS_ENABLED])->all();
        if ($map) {
            return ArrayHelper::map($result, 'id', 'name');
        }
        return $result;
    }
}