<?php

namespace common\models\activity\searchs;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\activity\Activity;

/**
 * ActivitySearch represents the model behind the search form of `common\models\platform\Activity`.
 */
class ActivitySearch extends Activity
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'start_time', 'end_time', 'status', 'view_count', 'join_count'], 'integer'],
            [['name', 'title', 'code', 'cover_url', 'share_poster_url', 'content', 'setting'], 'safe'],
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
        $query = Activity::find();

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
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'status' => $this->status,
            'view_count' => $this->view_count,
            'join_count' => $this->join_count,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'cover_url', $this->cover_url])
            ->andFilterWhere(['like', 'share_poster_url', $this->share_poster_url])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'setting', $this->setting]);

        return $dataProvider;
    }
}
