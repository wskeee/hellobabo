<?php

namespace common\models\platform\searchs;

use common\models\platform\AgencyOrder;
use common\models\platform\AgencyServicer;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AgencyServicerSearch represents the model behind the search form of `common\models\platform\AgencyServicer`.
 */
class AgencyOrderSearch extends AgencyOrder
{
    public $order_sn;
    public $user_name;
    public $user_phone;
    public $date_range;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'order_id', 'agency_id', 'is_self', 'created_at', 'updated_at'], 'integer'],
            [['order_sn', 'user_name', 'user_phone', 'date_range'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return parent::attributeLabels() + [
                'order_sn' => \Yii::t('app', 'Order Sn'),
                'user_name' => \Yii::t('app', '购买人名称'),
                'user_phone' => \Yii::t('app', '购买人电话'),
            ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
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
        $query = AgencyOrder::find()->alias('agencyOrder');

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

        $query->joinWith('order order')->with('agency');

        // grid filtering conditions
        $query->andFilterWhere([
            'agencyOrder.id' => $this->id,
            'agencyOrder.order_id' => $this->order_id,
            'agencyOrder.agency_id' => $this->agency_id,
            'agencyOrder.is_self' => $this->is_self,
            'order.order_sn' => $this->order_sn,
        ]);
        // 订单条件过滤
        $query->andFilterWhere(['like', 'order.consignee', $this->user_name]);
        $query->andFilterWhere(['like', 'order.phone', $this->user_phone]);

        // 时间过滤
        if (!empty($this->date_range)) {
            $ranges = array_filter(explode(' - ', $this->date_range));
            $start_time = strtotime("$ranges[0] 00:00:00");
            $end_time = strtotime("$ranges[1] 23:59:59");
            $query->andFilterWhere(['between', 'agencyOrder.created_at', $start_time, $end_time]);
        }


        return $dataProvider;
    }
}
