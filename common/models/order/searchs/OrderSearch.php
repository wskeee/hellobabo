<?php

namespace common\models\order\searchs;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\order\Order;

/**
 * OrderSearch represents the model behind the search form of `common\models\order\Order`.
 */
class OrderSearch extends Order
{

    public $date_range;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_status', 'country', 'province', 'city', 'district', 'town', 'is_recommend', 'recommend_by', 'created_by',], 'integer'],
            [['order_sn', 'consignee', 'date_range'], 'safe'],
            [['order_amount'], 'number'],
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
        $query = Order::find();

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

        if (!empty($this->date_range)) {
            $timeRang = array_filter(explode(' - ', $this->date_range));
            $start_time = strtotime("$timeRang[0] 00:00:00");
            $end_time = strtotime("$timeRang[1] 23:59:59");
            $query->andWhere(['between', 'created_at', $start_time, $end_time]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'order_status' => $this->order_status,
            'country' => $this->country,
            'province' => $this->province,
            'city' => $this->city,
            'district' => $this->district,
            'town' => $this->town,
            'is_recommend' => $this->is_recommend,
            'recommend_by' => $this->recommend_by,
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['like', 'order_sn', $this->order_sn])
                ->andFilterWhere(['like', 'pay_sn', $this->pay_sn])
                ->andFilterWhere(['like', 'consignee', $this->consignee])
                ->andFilterWhere(['like', 'phone', $this->phone]);

        return $dataProvider;
    }

}
