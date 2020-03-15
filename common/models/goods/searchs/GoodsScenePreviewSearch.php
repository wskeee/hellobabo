<?php

namespace common\models\goods\searchs;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\goods\GoodsScenePreview;

/**
 * GoodsScenePreviewSearch represents the model behind the search form of `common\models\goods\GoodsScenePreview`.
 */
class GoodsScenePreviewSearch extends GoodsScenePreview
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'goods_id', 'material_id', 'material_value_id', 'scene_id', 'is_del'], 'integer'],
            [['effect_url'], 'safe'],
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
        $query = GoodsScenePreview::find();

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

        $query->with('materialValue','scene');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'goods_id' => $this->goods_id,
            'material_id' => $this->material_id,
            'material_value_id' => $this->material_value_id,
            'scene_id' => $this->scene_id,
            'is_del' => $this->is_del,
        ]);

        $query->andFilterWhere(['like', 'effect_url', $this->effect_url]);

        return $dataProvider;
    }
}
