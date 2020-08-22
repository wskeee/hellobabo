<?php

namespace common\models\goods\searchs;

use common\models\goods\GoodsCategory;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * GoodsCategorySearch represents the model behind the search form of `common\models\goods\GoodsCategory`.
 */
class GoodsCategorySearch extends GoodsCategory
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name'], 'safe'],
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
        $this->load($params);
        
        //分页
        $page = ArrayHelper::getValue($params, 'page', 1);               
        //显示数
        $limit = ArrayHelper::getValue($params, 'limit', 20);
        
        // 查询类目
        $query = GoodsCategory::find();

        // 必要条件
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        // 模糊查询
        $query->andFilterWhere(['like', 'name', $this->name]);
        
        // 复制对象
        $queryCopy = clone $query;
        // 查询计算总数量
        $totalResults = $queryCopy->select(['COUNT(id) AS totalCount'])
            ->asArray()->one();
        
        // 显示数量
        $query->offset(($page - 1) * $limit)->limit($limit)->orderBy(['sort_order' => SORT_DESC]);
        return [
            'filter' => $params,
            'total' => $totalResults['totalCount'],
            'data' => [
                'category' => $query->all()
            ],
        ];
    }
}
