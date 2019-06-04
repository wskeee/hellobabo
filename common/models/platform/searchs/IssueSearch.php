<?php

namespace common\models\platform\searchs;

use common\models\platform\Issue;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * IssueSearch represents the model behind the search form of `common\models\platform\Issue`.
 */
class IssueSearch extends Issue
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'goods_id', 'order_id', 'result', 'status', 'type', 'handled_by', 'handled_at', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['feedback', 'content', 'contact_name', 'order_sn'], 'safe'],
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
        $query = Issue::find();

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
            'result' => $this->result,
            'status' => $this->status,
            'type' => $this->type,
            'handled_by' => $this->handled_by,
            'handled_at' => $this->handled_at,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'order_sn', $this->order_sn])
                ->andFilterWhere(['like', 'feedback', $this->feedback])
                ->andFilterWhere(['like', 'contact_name', $this->contact_name])
                ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }

}
