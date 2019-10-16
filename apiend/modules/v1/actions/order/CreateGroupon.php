<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\order\Groupon;
use common\models\order\OrderGoods;
use Exception;
use Yii;

/**
 * 创建团购
 *
 * @author Administrator
 */
class CreateGroupon extends BaseAction
{

    protected $requiredParams = ['order_goods_id'];

    public function run()
    {
        $order_goods_id = $this->getSecretParam('order_goods_id');
        $order_goods = OrderGoods::findOne(['id' => $order_goods_id]);
        $groupon = Groupon::find()
                ->where(['order_goods_id' => $order_goods_id])
                ->andWhere(['<>', 'status', Groupon::STATUS_INVALID])
                ->one();

        if ($groupon) {
            return new Response(Response::CODE_ORDER_GROUPON_CREATE_FAIL, '创建团购失败，重复创建！');
        }

        if (empty($order_goods) || $order_goods->status > OrderGoods::STATUS_UPLOAD_PIC_CHECK_FAIL) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => Yii::t('app', 'Order')]);
        }

        $tran = Yii::$app->db->beginTransaction();
        try {
            $groupon = new Groupon([
                'name' => $this->getSecretParam('name', $order_goods->goods_name) . '团购',
                'des' => $this->getSecretParam('des', '团购活动'),
                'cover_url' => $this->getSecretParam('cover_url', $order_goods->goods_img),
                'order_id' => $order_goods->order_id,
                'order_goods_id' => $order_goods_id,
                'goods_id' => $order_goods->goods_id,
                'goods_name' => $order_goods->goods_name,
                'goods_img' => $order_goods->goods_img,
                'goods_params' => $order_goods->goods_params,
                'spec_id' => $order_goods->spec_id,
                'spec_key' => $order_goods->spec_key,
                'spec_key_name' => $order_goods->spec_key_name,
                'amount' => $this->getSecretParam('amount', $order_goods->amount),
                'created_by' => Yii::$app->user->id,
            ]);
            if ($groupon->validate() && $groupon->save()) {
                $order_goods->groupon_id = $groupon->id;
                $order_goods->save();
                $tran->commit();
                return new Response(Response::CODE_COMMON_OK, null, $groupon);
            } else {
                return new Response(Response::CODE_ORDER_GROUPON_CREATE_FAIL, null, $order->getErrorSummary(true));
            }
        } catch (Exception $ex) {
            $tran->rollBack();
            return new Response(Response::CODE_ORDER_GROUPON_CREATE_FAIL, null, $ex->getMessage());
        }
    }

}
