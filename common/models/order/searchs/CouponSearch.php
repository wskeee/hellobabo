<?php

namespace common\models\order\searchs;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\order\Coupon;

/**
 * CouponSearch represents the model behind the search form of `common\models\order\Coupon`.
 */
class CouponSearch extends Coupon
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'used', 'type', 'with_special', 'with_id', 'with_amount', 'used_amount', 'quota', 'take_count', 'used_count', 'start_time', 'end_time', 'valid_type', 'valid_start_time', 'valid_end_time', 'valid_days', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['title', 'icon_url', 'des'], 'safe'],
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
        $query = Coupon::find();

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
            'used' => $this->used,
            'type' => $this->type,
            'with_special' => $this->with_special,
            'with_id' => $this->with_id,
            'with_amount' => $this->with_amount,
            'used_amount' => $this->used_amount,
            'quota' => $this->quota,
            'take_count' => $this->take_count,
            'used_count' => $this->used_count,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'valid_type' => $this->valid_type,
            'valid_start_time' => $this->valid_start_time,
            'valid_end_time' => $this->valid_end_time,
            'valid_days' => $this->valid_days,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'icon_url', $this->icon_url])
            ->andFilterWhere(['like', 'des', $this->des]);

        return $dataProvider;
    }
}
