<?php

namespace common\models\platform\searchs;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\platform\Express;

/**
 * ExpressSearch represents the model behind the search form of `common\models\platform\Express`.
 */
class ExpressSearch extends Express
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_del'], 'integer'],
            [['code', 'name', 'logo_url', 'des', 'config'], 'safe'],
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
        $query = Express::find();

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
            'is_del' => $this->is_del,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'logo_url', $this->logo_url])
            ->andFilterWhere(['like', 'des', $this->des])
            ->andFilterWhere(['like', 'config', $this->config]);

        return $dataProvider;
    }
}
