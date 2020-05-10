<?php

namespace common\models\goods\searchs;

use common\models\goods\GoodsScene;
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
        $query = GoodsScenePreview::find()->alias('preview');

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
        $query->leftJoin(['scene' => GoodsScene::tableName()],'scene.id = preview.scene_id');

        // grid filtering conditions
        $query->andFilterWhere([
            'preview.id' => $this->id,
            'preview.goods_id' => $this->goods_id,
            'preview.material_id' => $this->material_id,
            'preview.material_value_id' => $this->material_value_id,
            'preview.scene_id' => $this->scene_id,
            'preview.is_del' => $this->is_del,
        ]);

        $query->orderBy('scene.sort_order asc');

        return $dataProvider;
    }
}
