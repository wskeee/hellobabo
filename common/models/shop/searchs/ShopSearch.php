<?php

namespace common\models\shop\searchs;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\shop\Shop;

/**
 * ShopSearch represents the model behind the search form of `common\models\shop\Shop`.
 */
class ShopSearch extends Shop
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'status', 'goods_count', 'order_count', 'last_income_time'], 'integer'],
            [['name', 'logo_url', 'des', 'cover_url'], 'safe'],
            [['income_value', 'all_income', 'real_income', 'day_income', 'month_income'], 'number'],
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
        $query = Shop::find();

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
            'user_id' => $this->user_id,
            'income_value' => $this->income_value,
            'status' => $this->status,
            'goods_count' => $this->goods_count,
            'order_count' => $this->order_count,
            'all_income' => $this->all_income,
            'real_income' => $this->real_income,
            'day_income' => $this->day_income,
            'month_income' => $this->month_income,
            'last_income_time' => $this->last_income_time,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'logo_url', $this->logo_url])
            ->andFilterWhere(['like', 'des', $this->des])
            ->andFilterWhere(['like', 'cover_url', $this->cover_url]);

        return $dataProvider;
    }
}
