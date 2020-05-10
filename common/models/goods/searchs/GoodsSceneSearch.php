<?php

namespace common\models\goods\searchs;

use common\models\goods\GoodsPagePose;
use common\models\goods\GoodsScene;
use common\models\goods\GoodsSceneMaterial;
use common\models\goods\GoodsScenePage;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * GoodsSceneSearch represents the model behind the search form of `common\models\goods\GoodsScene`.
 */
class GoodsSceneSearch extends GoodsScene
{
    public $material_value_id;
    public $page_id;
    public $page_effect_url;
    public $page_is_required;
    public $pose_url;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'goods_id', 'group_id', 'sort_order', 'is_demo', 'immutable', 'is_required', 'is_selected', 'is_del'], 'integer'],
            [['material_value_id','page_is_required'], 'integer'],
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

    public function attributeLabels()
    {
        $arr = array_merge(parent::attributeLabels(), [
            'material_value_id' => Yii::t('app', 'Material'),
            'page_effect_url' => '上传效果图',
            'pose_url' => 'Pose图',
            'page_is_required' => '必须上图',
        ]);
        return $arr;
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
        $query = self::find()->alias('scene');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 200,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->leftJoin(['material_rel' => GoodsSceneMaterial::tableName()], 'material_rel.scene_id = scene.id');
        $query->leftJoin(['page' => GoodsScenePage::tableName()], 'page.scene_id = scene.id');
        $query->leftJoin(['pose' => GoodsPagePose::tableName()], 'page.id = pose.page_id');

        $query->select([
            'scene.*',
            'material_rel.material_value_id',
            'page.id page_id',
            'page.effect_url page_effect_url',
            'pose.filepath pose_url',
            'page.is_required page_is_required',
        ]);

        // grid filtering conditions
        $query->andFilterWhere([
            'scene.id' => $this->id,
            'scene.goods_id' => $this->goods_id,
            'scene.group_id' => $this->group_id,
            'scene.sort_order' => $this->sort_order,
            'scene.is_demo' => $this->is_demo,
            'scene.immutable' => $this->immutable,
            'scene.is_required' => $this->is_required,
            'scene.is_selected' => $this->is_selected,
            'scene.is_del' => $this->is_del,

            'material_rel.material_value_id' => $this->material_value_id,
            'page.is_required' => $this->page_is_required,
        ]);

        $query->andFilterWhere(['like', 'scene.name', $this->name])
            ->andFilterWhere(['like', 'scene.des', $this->des]);

        $query->orderBy(['scene.sort_order' => SORT_ASC]);
        return $dataProvider;
    }

}
