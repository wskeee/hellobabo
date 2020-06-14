<?php

namespace common\models\platform\searchs;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\platform\Comment;

/**
 * CommentSearch represents the model behind the search form of `common\models\platform\Comment`.
 */
class CommentSearch extends Comment
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'related_id', 'pid', 'depth', 'created_by', 'is_del', 'is_hide', 'created_at', 'updated_at'], 'integer'],
            [['title', 'content', 'thread'], 'safe'],
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
        $query = Comment::find();

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

        $query->with('creator');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'related_id' => $this->related_id,
            'pid' => $this->pid,
            'depth' => $this->depth,
            'created_by' => $this->created_by,
            'is_del' => $this->is_del,
            'is_hide' => $this->is_hide,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'thread', $this->thread]);

        if (isset($params['start_time'])) {
            $query->andFilterWhere(['>=', 'created_at', strtotime("{$params['start_time']} 00:00:00")]);
        }

        if (isset($params['end_time'])) {
            $query->andFilterWhere(['<=', 'created_at', strtotime("{$params['end_time']} 23:59:59")]);
        }

        $query->orderBy(['created_at desc']);


        return $dataProvider;
    }
}
