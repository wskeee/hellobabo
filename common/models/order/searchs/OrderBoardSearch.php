<?php

namespace common\models\order\searchs;

use common\models\order\Order;
use common\models\order\WorkflowDelivery;
use common\models\order\WorkflowDesign;
use common\models\order\WorkflowPrint;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\order\OrderGoods;
use yii\db\Query;

/**
 * OrderBoardSearch represents the model behind the search form of `common\models\order\OrderGoods`.
 */
class OrderBoardSearch extends Model
{
    // 订单号
    public $order_sn;
    // 收货人
    public $consignee;
    // 绘本ID
    public $goods_id;
    // 当前状态
    public $cur_status;
    // 跟进人
    public $follow_id;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cur_status', 'follow_id', 'goods_id'], 'integer'],
            [['order_sn', 'consignee'], 'safe'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = new Query();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params );

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->select([
            'Order.id order_id',
            'Order.order_sn order_sn',
            'Order.consignee consignee',
            'Order.pay_at pay_at',

            'OrderGoods.id order_goods_id',
            'OrderGoods.goods_name goods_name',
            'OrderGoods.goods_id goods_id',
            'OrderGoods.init_at init_at',
            'OrderGoods.upload_finish_at upload_finish_at',
            'OrderGoods.design_at design_at',
            'OrderGoods.print_at print_at',
            'OrderGoods.status cur_status',

            'Design.worker_id designer_id',
            'Print.worker_id printer_id',
            'Delivery.worker_id delivery_id',
        ]);

        // join
        $query->from(['Order' => Order::tableName()]);
        // 订单绘本
        $query->leftJoin(['OrderGoods' => OrderGoods::tableName()], 'Order.id = OrderGoods.order_id');
        // 设计
        $query->leftJoin(['Design' => WorkflowDesign::tableName()], 'Design.order_goods_id = OrderGoods.id');
        // 打印
        $query->leftJoin(['Print' => WorkflowPrint::tableName()], 'Print.order_goods_id = OrderGoods.id');
        // 发货
        $query->leftJoin(['Delivery' => WorkflowDelivery::tableName()], 'Delivery.order_id = Order.id');

        // grid filtering conditions
        $query->andFilterWhere([
            'OrderGoods.goods_id' => $this->goods_id,
            'OrderGoods.status' => $this->cur_status,
            'OrderGoods.is_del' => 0,
        ]);

        if ($this->cur_status != '0' || empty($this->cur_status)) {
            $query->andWhere(['<', 'OrderGoods.status', OrderGoods::STATUS_FINISH]);
        }

        $query->andWhere(['<>', 'OrderGoods.status', OrderGoods::STATUS_FINISH]);

        // 订单编号
        $query->andFilterWhere(['like', 'Order.order_sn', $this->order_sn]);
        // 收货人
        $query->andFilterWhere(['like', 'Order.consignee', $this->consignee]);
        // 跟进人
        if (!empty($this->follow_id)) {
            $query->andFilterWhere(['or',
                ['Design.worker_id' => $this->follow_id],
                ['Print.worker_id' => $this->follow_id],
                ['Delivery.worker_id' => $this->follow_id]]);
        }

        return $dataProvider;
    }
}
