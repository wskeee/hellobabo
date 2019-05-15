<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\platform\Order;

/**
 * 更换密码
 *
 * @author Administrator
 */
class ChangePassword extends BaseAction {

    public function run() {
        if (!$this->verify()) {
            return $this->verifyError;
        }

        /* 检查必须参数 */
        if ($notfounds = $this->checkRequiredParams($this->getSecretParams(), ['order_sn'])) {
            return new Response(Response::CODE_COMMON_MISS_PARAM, null, null, ['param' => implode(',', $notfounds)]);
        }

        $order = Order::findOne([
                    'order_sn' => $this->getSecretParam('order_sn'),
                    'order_status' => Order::STATUS_PAYED,
                    'created_by' => \Yii::$app->user->id,
        ]);

        if (!$order) {
            return new Response(Response::CODE_ORDER_CHANGE_PWD_FAILED, null, '找不到对应订单！');
        }
        if ($order->change_password_count > 0) {
            return new Response(Response::CODE_ORDER_CHANGE_PWD_FAILED, null, '最多只能更换一次密码！');
        }

        $tran = \Yii::$app->db->beginTransaction();
        try {
            $order->password = $order->makePwd();
            $order->change_password_count++;
            if ($order->save()) {
                $tran->commit();
                return new Response(Response::CODE_COMMON_OK);
            }else{
                throw new \Exception(implode(",", $order->getErrorSummary(true)));
            }
        } catch (\Exception $ex) {
            $tran->rollBack();
            return new Response(Response::CODE_COMMON_UNKNOWN, '更换密码失败，请重新再试！');
        }
    }

}
