<?php

namespace common\models\activity\searchs;

use common\models\activity\VoteActivityHandApply;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * VoteActivityHandApplySearch represents the model behind the search form of `common\models\activity\VoteActivityHandApply`.
 */
class VoteActivityHandApplySearch extends VoteActivityHandApply
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'activity_id', 'target_user_id', 'target_age', 'check_status', 'created_at', 'updated_at'], 'integer'],
            [['target_name', 'target_img', 'check_feedback'], 'safe'],
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
        $query = VoteActivityHandApply::find();

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
        $query->with('activity');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'activity_id' => $this->activity_id,
            'target_user_id' => $this->target_user_id,
            'target_age' => $this->target_age,
            'check_status' => $this->check_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'target_name', $this->target_name])
            ->andFilterWhere(['like', 'target_img', $this->target_img])
            ->andFilterWhere(['like', 'check_feedback', $this->check_feedback]);
        

        return $dataProvider;
    }
}
