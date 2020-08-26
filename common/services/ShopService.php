<?php


namespace common\services;


use common\models\order\Order;
use common\models\shop\Shop;
use common\models\shop\ShopSaleRecord;
use yii\db\Query;
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
            'shop_id' => $shop->id,
            'order_id' => $order->id,
            'income_value' => $shop->income_value,
            'real_income' => $real_income
        ];
        // 最近收益日期
        $last_income_date = date('Ymd', $shop->last_income_time);
        // 最近收益月
        $last_income_month = date('Ym', $shop->last_income_time);
        // 历史收益
        $shop->all_income += $real_income;
        // 当前收益
        $shop->real_income += $real_income;
        // 当日收益
        $shop->day_income = (date('Ymd') === $last_income_date ? $shop->day_income + $real_income : $real_income);
        // 当月收益
        $shop->month_income = (date('Ym') === $last_income_month ? $shop->month_income + $real_income : $real_income);
        // 更新最近收益日期
        $shop->last_income_time = time();
        // 更新订单数量
        $shop->order_count += 1;

        // 生成销售记录
        $shopSaleRecord = new ShopSaleRecord($rows);
        $tran = \Yii::$app->db->beginTransaction();
        try {
            // 更新商家
            // 保存商家销售记录
            if ($shop->save() && $shopSaleRecord->save()) {
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

    //----------------------------------------------------------------------
    //
    // 统计
    //
    //----------------------------------------------------------------------
    /**
     * 查询收益列表
     *
     * @param array $params
     * @param int $start_time 开始时间
     * @param int $end_time 结束时间
     * @param int $page
     * @param int $page_size
     * @return array
     */
    public static function getIncomeList($params, $start_time, $end_time, $page = 1, $page_size = 20)
    {
        $query = ShopSaleRecord::find()
            ->where($params)
            ->andFilterWhere(['>=', 'created_at', $start_time])
            ->andFilterWhere(['<=', 'created_at', $end_time])
            ->offset(($page - 1) * $page_size)
            ->limit($page_size);

        $total_query = clone $query;
        $total = $total_query->count();
        $list = $query->all();
        $data = [
            'total' => $total,
            'page' => $page,
            'page_size' => $page_size,
            'list' => $list,
        ];

        return $data;
    }
}