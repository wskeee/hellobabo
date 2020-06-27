<?php

namespace common\models\platform\searchs;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\platform\Agency;

/**
 * AgencySearch represents the model behind the search form of `common\models\platform\Agency`.
 */
class AgencySearch extends Agency
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'level', 'admin_id', 'province', 'city', 'district', 'town', 'status', 'apply_time', 'check_id', 'check_time', 'is_del', 'created_at', 'updated_at'], 'integer'],
            [['name', 'des', 'cover_url', 'contacts_name', 'contacts_phone', 'idcard', 'idcard_img_url', 'address', 'check_feedback'], 'safe'],
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
        $query = Agency::find();

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
            'level' => $this->level,
            'admin_id' => $this->admin_id,
            'province' => $this->province,
            'city' => $this->city,
            'district' => $this->district,
            'town' => $this->town,
            'status' => $this->status,
            'apply_time' => $this->apply_time,
            'check_id' => $this->check_id,
            'check_time' => $this->check_time,
            'is_del' => $this->is_del,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'des', $this->des])
            ->andFilterWhere(['like', 'cover_url', $this->cover_url])
            ->andFilterWhere(['like', 'contacts_name', $this->contacts_name])
            ->andFilterWhere(['like', 'contacts_phone', $this->contacts_phone])
            ->andFilterWhere(['like', 'idcard', $this->idcard])
            ->andFilterWhere(['like', 'idcard_img_url', $this->idcard_img_url])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'check_feedback', $this->check_feedback]);

        return $dataProvider;
    }
}
