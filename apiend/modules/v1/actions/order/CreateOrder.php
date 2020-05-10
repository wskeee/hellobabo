<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\goods\Goods;
use common\models\goods\GoodsSpecPrice;
use common\models\order\Groupon;
use common\models\order\Order;
use common\models\order\OrderGoods;
use common\models\order\OrderGoodsComment;
use common\models\order\OrderGoodsMaterial;
use common\models\order\OrderGoodsScene;
use common\models\order\OrderGoodsScenePage;
use Yii;

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
        //购买数量
        $goods_num = $this->getSecretParam('goods_num', 1);
        //留言
        $user_note = $this->getSecretParam('user_note', '');
        //推挤人ID
        $recommend_by = $this->getSecretParam('recommend_by', null);
        //团购ID
        $groupon_id = $this->getSecretParam('groupon_id', null);
        $goods_id = $this->getSecretParam('goods_id');
        //临时订单标识
        $temp_order_sign = $this->getSecretParam('temp_order_sign', null);

        $goods = Goods::findOne(['id' => $this->getSecretParam('goods_id')]); //绘本商品
        /* @var GoodsSpecPrice $spec_price */
        $spec_price = GoodsSpecPrice::find()->where(['spec_key' => $this->getSecretParam('gsp_key')])->one(); //价格
        $address_id = $this->getSecretParam('address_id');

        /* 检查订单状态 */
        if (!$goods || $goods->status != Goods::STATUS_PUBLISHED) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => \Yii::t('app', 'Goods')]);
        } else if (!$spec_price) {
            return new Response(Response::CODE_ORDER_CREATE_FAILED, '请选择规格！');
        } else if ($spec_price->store_count <= 0) {
            return new Response(Response::CODE_ORDER_STORE_COUNT_OUT);
        }

        /* 检查临时订单合法性 */
        $temp_order = GetTempOrder::getTempOrder($user_id, $goods_id);
        if ($temp_order['sign'] != $temp_order_sign) {
            return new Response(Response::CODE_ORDER_CREATE_FAILED, '订单数据出错！');
        }

        $tran = Yii::$app->db->beginTransaction();

        try {
            // 查询团购
            $groupon = Groupon::findGroupon($groupon_id);

            /* 创建订单 */
            list($order, $order_goods) = Order::createOrder($goods, $spec_price, $goods_num, $address_id, [
                'groupon_id' => $groupon_id,
                'recommend_by' => $recommend_by,
                'user_note' => $user_note,
            ]);

            //首个用户订单创建团购
            if ($goods->type == Goods::TYPE_GROUPON && $groupon_id == null) {
                $this->createGroupon($order_goods);
            }
            //添加团购关联
            if ($groupon_id != null) {
                Groupon::joinGroupon($groupon, $user_id, $order->id, $order_goods->id);
            }

            // 初始订单默认数据
            $this->initDefault($order_goods);

            // 初始订单留言
            $this->initComment($order_goods);

            // 提交数据
            $tran->commit();
            // 清除临时订单
            GetTempOrder::clearTempOrder($user_id, $goods_id);

            return new Response(Response::CODE_COMMON_OK, null, $order);
        } catch (\Exception $ex) {
            $tran->rollBack();
            return new Response(Response::CODE_ORDER_CREATE_FAILED, '下单失败', $ex->getMessage());
        }
    }

    /**
     * 创建团购
     *
     * @param OrderGoods $order_goods
     */
    private function createGroupon($order_goods)
    {
        return Groupon::createGroupon(
            $this->getSecretParam('name', $order_goods->goods_name) . '团购',
            $this->getSecretParam('des', '团购活动'),
            $this->getSecretParam('cover_url', $order_goods->goods_img),
            $this->getSecretParam('amount', $order_goods->amount),
            $order_goods);
    }

    /**
     * 订单商品
     * @param OrderGoods $order_goods
     * @throws Exception
     */
    private function initDefault($order_goods)
    {
        $user_id = Yii::$app->user->id;
        // 临时订单
        $temp_order = GetTempOrder::getTempOrder($user_id, $order_goods->goods_id);
        // 已选择素材
        $material_value_ids = [$temp_order['material_value_id']];
        // 已选场景
        $scenes_ids = $temp_order['scenes'];

        // 初始素材
        OrderGoodsMaterial::initDefaultMaterial($order_goods, $material_value_ids);
        // 初始场景
        OrderGoodsScene::initDefaultScene($order_goods, $scenes_ids , $temp_order['material_value_id']);
        // 初始场景页
        OrderGoodsScenePage::initPage($order_goods);
    }

    /**
     * 添加初始留意
     * @param OrderGoods $order_goods
     * @throws
     */
    private function initComment($order_goods)
    {
        $user_id = $order_goods->created_by;
        $count = OrderGoods::find()->where(['created_by' => $user_id])->count();
        $count ++;
        $params = [
            'order_goods_id' => $order_goods->id,
            'content' => "宝贝的第{$count}套个性化绘本",
            'created_by' => $user_id,
        ];
        $model = new OrderGoodsComment($params);
        $model->loadDefaultValues();
        if(!$model->save()){
            throw new \Exception(implode(',',$model->getErrorSummary(true)));
        }

        $params['content'] = '今天开始跟宝贝制作绘本啦';
        $model = new OrderGoodsComment($params);
        $model->loadDefaultValues();
        if(!$model->save()){
            throw new \Exception(implode(',',$model->getErrorSummary(true)));
        }
    }
}
