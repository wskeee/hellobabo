<?php

namespace common\models\goods\searchs;

use common\models\goods\Goods;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * GoodsSearch represents the model behind the search form of `common\models\goods\Goods`.
 */
class GoodsSearch extends Goods
{
    /* 关键字搜索 */
    public $keyword = '';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'model_id', 'owner_id', 'status', 'store_count', 'comment_count', 'click_count', 'share_count', 'like_count', 'sale_count', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['goods_sn', 'goods_name', 'goods_des', 'cover_url', 'video_url', 'tags'], 'safe'],
            [['goods_cost', 'goods_price'], 'number'],
            [['keyword'], 'string'],
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
        $query = Goods::find();

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
            'goods_sn' => $this->goods_sn,
            'category_id' => $this->category_id,
            'owner_id' => $this->owner_id,
            'status' => $this->status,
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['or', ['like', 'tags', $this->keyword], ['like', 'goods_name', $this->keyword]]);
        $query->orderBy(['is_top' => SORT_DESC, 'sort_order' => SORT_DESC]);

        return $dataProvider;
    }

    public function attributeLabels()
    {
        return parent::attributeLabels() + ['keyword' => Yii::t('app', 'Keyword')];
    }
}
