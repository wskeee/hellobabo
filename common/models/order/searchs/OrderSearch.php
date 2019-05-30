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
            [['goods_id', 'spec_id', 'order_status', 'work_status', 'country', 'province', 'city', 'district', 'town', 'is_recommend', 'recommend_by', 'created_by',], 'integer'],
            [['order_sn', 'goods_name', 'spec_key', 'spec_key_name', 'consignee', 'date_range'], 'safe'],
            [['goods_price', 'order_amount'], 'number'],
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
            $query->andWhere(['between', 'created_at', $timeRang[0], $timeRang[1]]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'goods_id' => $this->goods_id,
            'spec_id' => $this->spec_id,
            'order_status' => $this->order_status,
            'work_status' => $this->work_status,
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
                ->andFilterWhere(['like', 'goods_name', $this->goods_name])
                ->andFilterWhere(['like', 'spec_key_name', $this->spec_key_name])
                ->andFilterWhere(['like', 'pay_sn', $this->pay_sn])
                ->andFilterWhere(['like', 'consignee', $this->consignee])
                ->andFilterWhere(['like', 'phone', $this->phone]);

        return $dataProvider;
    }

}