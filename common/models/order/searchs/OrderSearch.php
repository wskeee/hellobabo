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
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'goods_id', 'goods_num', 'spec_id', 'order_status', 'work_status', 'play_at', 'init_at', 'upload_finish_at', 'design_at', 'print_at', 'shipping_at', 'confirm_at', 'country', 'province', 'city', 'district', 'town', 'is_recommend', 'recommend_by', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['order_sn', 'goods_name', 'spec_key', 'spec_key_name', 'user_note', 'play_code', 'play_sn', 'consignee', 'zipcode', 'phone', 'address'], 'safe'],
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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'goods_id' => $this->goods_id,
            'goods_price' => $this->goods_price,
            'goods_num' => $this->goods_num,
            'spec_id' => $this->spec_id,
            'order_amount' => $this->order_amount,
            'order_status' => $this->order_status,
            'work_status' => $this->work_status,
            'play_at' => $this->play_at,
            'init_at' => $this->init_at,
            'upload_finish_at' => $this->upload_finish_at,
            'design_at' => $this->design_at,
            'print_at' => $this->print_at,
            'shipping_at' => $this->shipping_at,
            'confirm_at' => $this->confirm_at,
            'country' => $this->country,
            'province' => $this->province,
            'city' => $this->city,
            'district' => $this->district,
            'town' => $this->town,
            'is_recommend' => $this->is_recommend,
            'recommend_by' => $this->recommend_by,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'order_sn', $this->order_sn])
            ->andFilterWhere(['like', 'goods_name', $this->goods_name])
            ->andFilterWhere(['like', 'spec_key', $this->spec_key])
            ->andFilterWhere(['like', 'spec_key_name', $this->spec_key_name])
            ->andFilterWhere(['like', 'user_note', $this->user_note])
            ->andFilterWhere(['like', 'play_code', $this->play_code])
            ->andFilterWhere(['like', 'play_sn', $this->play_sn])
            ->andFilterWhere(['like', 'consignee', $this->consignee])
            ->andFilterWhere(['like', 'zipcode', $this->zipcode])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'address', $this->address]);

        return $dataProvider;
    }
}
