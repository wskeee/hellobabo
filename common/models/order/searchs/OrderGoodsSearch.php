<?php

namespace common\models\order\searchs;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\order\OrderGoods;

/**
 * OrderGoodsSearch represents the model behind the search form of `common\models\order\OrderGoods`.
 */
class OrderGoodsSearch extends OrderGoods
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'order_id', 'goods_id', 'goods_num', 'spec_id', 'scene_num', 'status', 'init_at', 'upload_finish_at', 'design_at', 'print_at', 'is_del', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['order_sn', 'goods_name', 'goods_img', 'user_cover_url', 'spec_key', 'spec_key_name'], 'safe'],
            [['goods_price', 'goods_cost', 'amount'], 'number'],
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
        $query = OrderGoods::find();

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
            'order_id' => $this->order_id,
            'goods_id' => $this->goods_id,
            'goods_price' => $this->goods_price,
            'goods_cost' => $this->goods_cost,
            'goods_num' => $this->goods_num,
            'spec_id' => $this->spec_id,
            'scene_num' => $this->scene_num,
            'amount' => $this->amount,
            'status' => !empty($this->status) ? $this->status : [OrderGoods::STATUS_UPLOAD_PIC_CHECK, OrderGoods::STATUS_DESIGN_CHECK, OrderGoods::STATUS_PRINT_CHECK],
            'init_at' => $this->init_at,
            'upload_finish_at' => $this->upload_finish_at,
            'design_at' => $this->design_at,
            'print_at' => $this->print_at,
            'is_del' => $this->is_del,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'order_sn', $this->order_sn])
            ->andFilterWhere(['like', 'goods_name', $this->goods_name])
            ->andFilterWhere(['like', 'goods_img', $this->goods_img])
            ->andFilterWhere(['like', 'user_cover_url', $this->user_cover_url])
            ->andFilterWhere(['like', 'spec_key', $this->spec_key])
            ->andFilterWhere(['like', 'spec_key_name', $this->spec_key_name]);

        return $dataProvider;
    }
}
