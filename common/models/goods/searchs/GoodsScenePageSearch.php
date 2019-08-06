<?php

namespace common\models\goods\searchs;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\goods\GoodsScenePage;

/**
 * GoodsScenePageSearch represents the model behind the search form of `common\models\goods\GoodsScenePage`.
 */
class GoodsScenePageSearch extends GoodsScenePage
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'scene_id', 'angle_id', 'face_id', 'is_required', 'is_del'], 'integer'],
            [['name', 'effect_url', 'source_url', 'pos', 'des'], 'safe'],
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
        $query = GoodsScenePage::find();

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
            'scene_id' => $this->scene_id,
            'angle_id' => $this->angle_id,
            'face_id' => $this->face_id,
            'is_required' => $this->is_required,
            'is_del' => $this->is_del,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'effect_url', $this->effect_url])
            ->andFilterWhere(['like', 'source_url', $this->source_url])
            ->andFilterWhere(['like', 'pos', $this->pos])
            ->andFilterWhere(['like', 'des', $this->des]);

        return $dataProvider;
    }
}
