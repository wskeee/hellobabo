<?php

namespace common\models\order\searchs;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\order\WorkflowPrint;

/**
 * WorkflowPrintSearch represents the model behind the search form of `common\models\order\WorkflowPrint`.
 */
class WorkflowPrintSearch extends WorkflowPrint
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'order_id', 'status', 'start_at', 'end_at', 'worker_id', 'created_at', 'updated_at'], 'integer'],
            [['order_sn'], 'safe'],
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
        $query = WorkflowPrint::find();

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
            'status' => $this->status,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'worker_id' => $this->worker_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'order_sn', $this->order_sn]);

        return $dataProvider;
    }
}
