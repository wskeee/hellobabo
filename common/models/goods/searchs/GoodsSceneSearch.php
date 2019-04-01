<?php

namespace common\models\goods\searchs;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\goods\GoodsScene;

/**
 * GoodsSceneSearch represents the model behind the search form of `common\models\goods\GoodsScene`.
 */
class GoodsSceneSearch extends GoodsScene
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'goods_id', 'group_id', 'sort_order', 'immutable', 'is_required', 'is_selected', 'is_del'], 'integer'],
            [['name', 'effect_url', 'demo_url', 'source_url', 'des'], 'safe'],
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
        $query = GoodsScene::find();

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
            'group_id' => $this->group_id,
            'sort_order' => $this->sort_order,
            'immutable' => $this->immutable,
            'is_required' => $this->is_required,
            'is_selected' => $this->is_selected,
            'is_del' => $this->is_del,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'effect_url', $this->effect_url])
            ->andFilterWhere(['like', 'demo_url', $this->demo_url])
            ->andFilterWhere(['like', 'source_url', $this->source_url])
            ->andFilterWhere(['like', 'des', $this->des]);
        
        $query->orderBy(['sort_order' => SORT_ASC]);

        return $dataProvider;
    }
}
