<?php

namespace common\models\goods\searchs;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\goods\GoodsScenePageMaterialItem;

/**
 * GoodsScenePageMaterialItemSearch represents the model behind the search form of `common\models\goods\GoodsScenePageMaterialItem`.
 */
class GoodsScenePageMaterialItemSearch extends GoodsScenePageMaterialItem
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'scene_page_id', 'material_value_id', 'material_value_item_id', 'is_del'], 'integer'],
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
        $query = GoodsScenePageMaterialItem::find();

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
            'scene_page_id' => $this->scene_page_id,
            'material_value_id' => $this->material_value_id,
            'material_value_item_id' => $this->material_value_item_id,
            'is_del' => $this->is_del,
        ]);

        return $dataProvider;
    }
}
