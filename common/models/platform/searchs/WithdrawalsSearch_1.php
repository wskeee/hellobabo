<?php

namespace common\models\platform\searchs;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\platform\Withdrawals;

/**
 * WithdrawalsSearch represents the model behind the search form of `common\models\platform\Withdrawals`.
 */
class WithdrawalsSearch extends Withdrawals
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'need_check', 'check_user_id', 'check_at', 'pay_at', 'status', 'created_at', 'updated_at'], 'integer'],
            [['order_sn', 'check_feedback', 'tran_sn', 'pay_account', 'pay_realname', 'pay_code', 'pay_error_code', 'des'], 'safe'],
            [['amount'], 'number'],
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
        $query = Withdrawals::find();

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
            'amount' => $this->amount,
            'need_check' => $this->need_check,
            'check_user_id' => $this->check_user_id,
            'check_at' => $this->check_at,
            'pay_at' => $this->pay_at,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'order_sn', $this->order_sn])
            ->andFilterWhere(['like', 'check_feedback', $this->check_feedback])
            ->andFilterWhere(['like', 'tran_sn', $this->tran_sn])
            ->andFilterWhere(['like', 'pay_account', $this->pay_account])
            ->andFilterWhere(['like', 'pay_realname', $this->pay_realname])
            ->andFilterWhere(['like', 'pay_code', $this->pay_code])
            ->andFilterWhere(['like', 'pay_error_code', $this->pay_error_code])
            ->andFilterWhere(['like', 'des', $this->des]);
        
        $query->orderBy(['created_at' => SORT_DESC]);

        return $dataProvider;
    }
}
