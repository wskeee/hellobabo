<?php

namespace common\models\goods\searchs;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\goods\GoodsMaterialValueItem;

/**
 * GoodsMaterialValueItemSearch represents the model behind the search form of `common\models\goods\GoodsMaterialValueItem`.
 */
class GoodsMaterialValueItemSearch extends GoodsMaterialValueItem
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'material_value_id', 'is_del'], 'integer'],
            [['source_id', 'name', 'effect_url', 'source_url', 'des'], 'safe'],
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
        $query = GoodsMaterialValueItem::find();

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
            'material_value_id' => $this->material_value_id,
            'is_del' => $this->is_del,
        ]);

        $query->andFilterWhere(['like', 'source_id', $this->source_id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'effect_url', $this->effect_url])
            ->andFilterWhere(['like', 'source_url', $this->source_url])
            ->andFilterWhere(['like', 'des', $this->des]);

        return $dataProvider;
    }
}
