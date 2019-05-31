<?php

namespace common\models\order\searchs;

use common\models\order\Order;
use common\models\order\WorkflowDelivery;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * WorkflowDeliverySearch represents the model behind the search form of `common\models\order\WorkflowDelivery`.
 */
class WorkflowDeliverySearch extends WorkflowDelivery
{
    public $order_sn; //订单sn
    public $consignee; //联系人
    public $date_range; //时间段内任务 格式：2019-04-10 - 2019-04-15

    /**
     * {@inheritdoc}
     */

    public function rules()
    {
        return [
            [['status', 'worker_id',], 'integer'],
            [['date_range',], 'string'],
            [['order_sn', 'consignee'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return parent::attributeLabels();
        return [
            'order_sn' => Yii::t('app', 'Order Sn'),
            'goods_name' => Yii::t('app', 'Goods'),
            'spec_key_name' => Yii::t('app', 'Spec'),
            'status' => Yii::t('app', 'Status'),
            'consignee' => Yii::t('app', 'Contacter'),
            'start_at' => Yii::t('app', 'Start At'),
            'end_at' => Yii::t('app', 'End At'),
            'date_range' => Yii::t('app', 'Time'),
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
        $query = WorkflowDelivery::find()->from(['Delivery' => WorkflowDelivery::tableName()]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        $query->leftJoin(['Order' => Order::tableName()], 'Order.id = order_id');

        // grid filtering conditions
        $query->andFilterWhere([
            'Delivery.status' => $this->status,
            'Delivery.worker_id' => $this->worker_id,
        ]);

        if (!empty($this->date_range)) {
            $timeRang = array_filter(explode(' - ', $this->date_range));
            $query->andWhere(['between', 'Delivery.created_at', $timeRang[0], $timeRang[1]]);
        }

        $query->andFilterWhere(['like', 'Order.order_sn', $this->order_sn]);
        $query->andFilterWhere(['like', 'Order.consignee', $this->consignee]);

        return $dataProvider;
    }
}
