<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\platform\Goods;
use common\models\platform\GoodsActionLog;
use common\models\platform\Order;
use Yii;

/**
 * 更换设置
 *
 * @author Administrator
 */
class ChangeGoods extends BaseAction {

    public function run() {
        if (!$this->verify()) {
            return $this->verifyError;
        }

        /* 检查必须参数 */
        if ($notfounds = $this->checkRequiredParams($this->getSecretParams(), ['order_sn', 'goods_sn', 'reason'])) {
            return new Response(Response::CODE_COMMON_MISS_PARAM, null, null, ['param' => implode(',', $notfounds)]);
        }

        /* 检查设备状态 */
        $goods = Goods::findOne(['goods_sn' => $this->getSecretParam('goods_sn')]);
        if (!$goods || empty($goods->agency_id) || empty($goods->merchant_id)) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => Yii::t('app', 'Goods')]);
        } else if ($goods->status != Goods::STATUS_FREE) {
            return new Response(Response::CODE_ORDER_CHANGE_GOODS_FAILED,'更换失败！'. Yii::t('app', 'Goods') . Goods::$statusKeyMap[$goods->status]);
        }

        /* 检查订单状态 */
        $order = Order::findOne([
                    'order_sn' => $this->getSecretParam('order_sn'),
                    'order_status' => Order::STATUS_PAYED,
                    'created_by' => Yii::$app->user->id,
        ]);
        if (!$order) {
            return new Response(Response::CODE_ORDER_CHANGE_GOODS_FAILED, '找不到对应订单！');
        }
        if ($order->change_goods_count > 0) {
            return new Response(Response::CODE_ORDER_CHANGE_GOODS_FAILED, '最多只能更换一次设备！' );
        }

        /** 更换操作 */
        $old_goods = $order->goods;
        $order->change_goods_count ++;
        $order->goods_id = $goods->id;
        $order->goods_sn = $goods->goods_sn;
        $order->password = $order->makePwd();
        //设置被更换设备为故障状态
        $old_goods->status = Goods::STATUS_BAD;
        $goods->status = Goods::STATUS_RUNING;
        $tran = \Yii::$app->db->beginTransaction();
        try {
            $order->save();
            $goods->save();
            $old_goods->save();
            GoodsActionLog::saveLog($old_goods->id, '设备故障', $this->getSecretParam('reason'));
            $tran->commit();
            return new Response(Response::CODE_COMMON_OK);
        } catch (\Exception $ex) {
            $tran->rollBack();
            return new Response(Response::CODE_COMMON_UNKNOWN, '更换设备失败，请重新再试！');
        }
    }

}
