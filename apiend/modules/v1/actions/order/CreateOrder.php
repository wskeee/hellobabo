<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\goods\Goods;
use common\models\goods\GoodsSpecPrice;
use common\models\order\Groupon;
use common\models\order\GrouponRecord;
use common\models\order\Order;
use common\models\order\OrderAction;
use common\models\order\OrderGoods;
use common\models\order\OrderGoodsAction;
use common\models\UserAddress;
use Yii;
use yii\base\Exception;

/**
 * 下单
 *
 * @author Administrator
 */
class CreateOrder extends BaseAction
{
    /* 必须参数 */

    protected $requiredParams = ['goods_id', 'gsp_key', 'address_id'];

    public function run()
    {
        $user_id = Yii::$app->user->id;
        $goods_num = $this->getSecretParam('goods_num', 1); //购买数量 
        $user_note = $this->getSecretParam('user_note', ''); //留言
        $recommend_by = $this->getSecretParam('recommend_by', null); //推挤人ID
        $groupon_id = $this->getSecretParam('groupon_id', null); //团购ID

        $goods = Goods::findOne(['id' => $this->getSecretParam('goods_id')]); //绘本商品
        $spec_price = GoodsSpecPrice::find()->where(['spec_key' => $this->getSecretParam('gsp_key')])->one(); //价格
        $address = UserAddress::findOne(['id' => $this->getSecretParam('address_id')]); //地址

        /* 检查订单状态 */
        if (!$goods || $goods->status != Goods::STATUS_PUBLISHED) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => \Yii::t('app', 'Goods')]);
        } else if ($spec_price->store_count <= 0) {
            return new Response(Response::CODE_ORDER_STORE_COUNT_OUT);
        }

        $tran = Yii::$app->db->beginTransaction();

        try {
            // 加入团购
            if ($groupon_id != null) {
                $groupon = Groupon::findOne(['id' => $groupon_id, 'status' => Groupon::STATUS_RUNING]);
                if ($groupon == null) {
                    throw new Exception('团购不存在！');
                } else if ($groupon->status == Groupon::STATUS_FINISHED) {
                    throw new Exception('来晚啦，该团购已满！');
                }
            }
            /* 创建订单 */
            $order = new Order([
                'order_sn' => Order::getRandomSN(),
                'order_amount' => $spec_price->goods_price * $goods_num, //订单总额使用套餐价格
                //推荐
                'is_recommend' => $recommend_by != null ? 1 : 0, //是否为推荐订单
                'recommend_by' => $recommend_by, //推挤人ID
                //收货地址
                'address_id' => $address->id, //地址ID
                'user_note' => $user_note, //留言
                'consignee' => $address->consignee, //收货人
                'zipcode' => $address->zipcode,
                'phone' => $address->phone,
                'province' => $address->province,
                'city' => $address->city,
                'district' => $address->district,
                'town' => $address->town,
                'address' => $address->address,
                'created_by' => $user_id,
            ]);
            if ($order->save()) {
                OrderAction::saveLog([$order->id], '创建订单', '');
                /* 创建商品 */
                $order_goods = new OrderGoods([
                    'order_id' => $order->id,
                    'order_sn' => $order->order_sn,
                    'created_by' => $user_id,
                    'groupon_id' => $groupon_id,
                    'type' => $goods->type,
                    //商品
                    'goods_id' => $goods->id,
                    'goods_name' => $goods->goods_name, //商品名
                    'goods_img' => $goods->cover_url, //图片
                    'goods_price' => $spec_price->goods_price, //商品价格
                    'goods_num' => $goods_num, //购买数量
                    'goods_params' => $goods->params, //商品参数
                    'scene_num' => $spec_price->scene_num, //购买场景数量
                    'spec_id' => $spec_price->id, //价格ID
                    'spec_key' => $spec_price->spec_key, //价格项ID
                    'spec_key_name' => $spec_price->spec_key_name, //价格项名
                    'amount' => $spec_price->goods_price * $goods_num, //总价
                ]);

                if ($order_goods->save()) {
                    OrderGoodsAction::saveLog([$order_goods->id], '订单创建', '');
                    //首个用户订单创建团购
                    if($goods->type == Goods::TYPE_GROUPON && $groupon_id == null){
                        $this->createGroupon($order_goods);
                    }
                    //添加团购关联
                    if ($groupon_id != null) {
                        $groupon_record = new GrouponRecord([
                            'user_id' => $user_id,
                            'groupon_id' => $groupon_id,
                            'order_id' => $order->id,
                            'order_goods_id' => $order_goods->id,
                        ]);
                        $groupon_record->save();

                        // 检查是否满员
                        $role_num = json_decode($groupon->goods_params)->role_num;
                        $count = GrouponRecord::find()->where(['groupon_id' => $groupon_id])->andWhere(['<>', 'status', GrouponRecord::STATUS_INVALID])->count();
                        if ($count >= $role_num) {
                            $groupon->status = Groupon::STATUS_FINISHED;
                            $groupon->save();
                        }
                    }
                    $tran->commit();
                    return new Response(Response::CODE_COMMON_OK, null, $order);
                } else {
                    throw new Exception(implode(',', $order_goods->getErrorSummary(true)));
                }
            } else {
                throw new Exception(implode(',', $order->getErrorSummary(true)));
            }
        } catch (Exception $ex) {
            $tran->rollBack();
            return new Response(Response::CODE_ORDER_CREATE_FAILED, '下单失败', $ex->getMessage());
        }
    }

    /**
     * 创建团购
     * 
     * @param type $order
     * @param type $orderGoods
     */
    private function createGroupon($order_goods)
    {
        $groupon = new Groupon([
            'name' => $this->getSecretParam('name', $order_goods->goods_name) . '团购',
            'des' => $this->getSecretParam('des', '团购活动'),
            'cover_url' => $this->getSecretParam('cover_url', $order_goods->goods_img),
            'order_id' => $order_goods->order_id,
            'order_goods_id' => $order_goods->id,
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
            $record = new GrouponRecord([
                'groupon_id' => $groupon->id,
                'user_id' => Yii::$app->user->id,
                'order_id' => $order_goods->order_id,
                'order_goods_id' => $order_goods->id,
                'status' => GrouponRecord::STATUS_SUCCESS,
            ]);
            $record->save();
        } else {
            throw new Exception(implode(',', $groupon->getErrorSummary(true)));
        }
    }

}
