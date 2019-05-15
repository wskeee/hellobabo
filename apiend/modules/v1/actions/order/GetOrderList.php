<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\platform\Merchant;
use common\models\platform\Order;
use Yii;
use yii\db\Query;

class GetOrderList extends BaseAction {

    public function run() {
        if (!$this->verify()) {
            return $this->verifyError;
        }

        $order_status = $this->getSecretParam('order_status', null);
        $orders = (new Query())
                ->select([
                    'Order.order_sn', 'Order.order_status', 'Order.created_at', 'Order.goods_name', 'Order.goods_price',
                    'Merchant.id merchant_id', 'Merchant.name merchant_name', 'Merchant.cover_url merchant_cover'
                ])
                ->from(['Order' => Order::tableName()])
                ->leftJoin(['Merchant' => Merchant::tableName()], 'Order.merchant_id = Merchant.id')
                ->where(['Order.created_by' => Yii::$app->user->id])
                ->andFilterWhere(['Order.order_status' => $order_status])
                ->limit(20)
                ->orderBy(['Order.created_at' => SORT_DESC])
                ->all();

        return new Response(Response::CODE_COMMON_OK, null, $orders);
    }

}
