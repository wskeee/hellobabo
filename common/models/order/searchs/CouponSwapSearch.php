<?php

namespace common\models\order\searchs;

use common\models\order\Coupon;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\order\CouponSwap;
use yii\db\ActiveQuery;

/**
 * CouponSwapSearch represents the model behind the search form of `common\models\order\CouponSwap`.
 *
 * @property Coupon $coupon 优惠卷
 */
class CouponSwapSearch extends CouponSwap
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'coupon_id', 'is_swap', 'swap_id', 'swap_at', 'is_del', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['code', 'condition'], 'safe'],
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
        $query = CouponSwap::find();

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
        $query->with('creator');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'coupon_id' => $this->coupon_id,
            'is_swap' => $this->is_swap,
            'swap_id' => $this->swap_id,
            'swap_at' => $this->swap_at,
            'is_del' => $this->is_del,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'condition', $this->condition]);

        return $dataProvider;
    }

    /**
     * @return ActiveQuery
     */
    public function getCoupon()
    {
        return $this->hasOne(Coupon::class, ['id' => 'coupon_id']);
    }
}
