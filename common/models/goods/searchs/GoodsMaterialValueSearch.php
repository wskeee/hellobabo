<?php

namespace common\models\goods\searchs;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\goods\GoodsMaterialValue;

/**
 * GoodsMaterialValueSearch represents the model behind the search form of `common\models\goods\GoodsMaterialValue`.
 */
class GoodsMaterialValueSearch extends GoodsMaterialValue
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'material_id', 'is_selected', 'is_del'], 'integer'],
            [['name', 'effect_url', 'source_url'], 'safe'],
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
        $query = GoodsMaterialValue::find();

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
            'material_id' => $this->material_id,
            'is_selected' => $this->is_selected,
            'is_del' => $this->is_del,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'effect_url', $this->effect_url])
            ->andFilterWhere(['like', 'source_url', $this->source_url]);
        
        $query->orderBy(['sort_order' => SORT_ASC]);

        return $dataProvider;
    }
}
