<?php


namespace common\services;


use common\models\order\Order;
use common\models\platform\Agency;
use common\models\platform\AgencyOrder;
use common\models\platform\AgencyServicer;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

class AgencyService
{
    use BaseServiceTrait;

    /**
     * 获取所有代理
     * @return array
     */
    public static function getAllAgencys()
    {
        $result = Agency::find()->select(['id', 'name'])->asArray()->all();
        return ArrayHelper::map($result, 'id', 'name');
    }

    /**
     * 订单完成
     * @param int $order_id
     */
    public static function orderPay($order_id)
    {
        // 订单
        $order = Order::findOne(['id' => $order_id, 'order_status' => Order::ORDER_STATUS_WAIT_DELIVER]);
        if (!$order) {
            return self::fail([], '找不到订单数据');
        }
        // 批量更新 [agency_id,order_num_all,order_num_self,order_num_auto]
        $rows = [];
        // 按下单人找代理
        $agency = self::getAgencyByServiceId($order->created_by);

        // 自动归纳订单代理
        $autoAgencys = [];
        // 按钮地区找代理
        $autoAgencys[] = self::getAgencyByRegion(Agency::LEVEL_DISTRICT, $order->district);
        $autoAgencys[] = self::getAgencyByRegion(Agency::LEVEL_CITY, $order->city);
        $autoAgencys[] = self::getAgencyByRegion(Agency::LEVEL_PROVINCE, $order->province);

        // 过滤空
        $autoAgencys = array_filter($autoAgencys);
        if(!$agency || empty($autoAgencys)) {
            return self::success();
        }
        // 增加代理订单数据
        $tran = \Yii::$app->db->beginTransaction();
        $rows = [];
        $now = time();
        try {
            if ($agency) {
                // 增加总订单和自身订单计数
                Agency::updateAllCounters(['order_num_all' => 1, 'order_num_self' => 1], ['id' => $agency->id]);
                // 关联代理商与订单
                $rows = [[$agency->id, $order_id, 1, $now, $now]];
            }
            // 增加父级代理商订单计数
            $autoAgencyIds = array_column($autoAgencys, 'id');
            // 有自身代理情况下需要过滤自身代理
            $agency && $autoAgencyIds = array_diff($autoAgencyIds, [$agency->id]);

            if (count($autoAgencyIds)) {
                // 增加总订单
                Agency::updateAllCounters(['order_num_all' => 1], ['id' => $autoAgencyIds]);
                // 关联订单
                foreach ($autoAgencyIds as $aid) {
                    $rows[] = [$aid, $order_id, 0, $now, $now];
                }
            }
            // 生成关联订单
            \Yii::$app->db->createCommand()->batchInsert(
                AgencyOrder::tableName(), ['agency_id', 'order_id', 'is_self', 'created_at', 'updated_at'], $rows)->execute();

            $tran->commit();
            return self::success();
        } catch (Exception $e) {
            $tran->rollBack();
            return self::fail($e, $e->getMessage());
        }
    }

    /**
     * 通过客服ID查找代理商
     * @param int $user_id
     */
    public static function getAgencyByServiceId($user_id)
    {
        $model = AgencyServicer::findOne(['user_id' => $user_id, 'is_del' => 0]);
        return $model ? $model->agency : null;
    }

    /**
     * 通过管理ID查询代理商
     * @param int $admin_id
     */
    public static function getAgencyByAdminId($admin_id)
    {
        $model = Agency::findOne(['admin_id' => $admin_id, 'is_del' => 0]);
        return $model;
    }

    /**
     * 通过地区ID查找代理商
     * @param int $region_id
     */
    public static function getAgencyByRegion($level, $region_id)
    {
        $attr = [
            Agency::LEVEL_COUNTRY => 'country',
            Agency::LEVEL_PROVINCE => 'province',
            Agency::LEVEL_CITY => 'city',
            Agency::LEVEL_DISTRICT => 'district',
        ];
        return Agency::findOne(['level' => $level, $attr[$level] => $region_id]);
    }
}