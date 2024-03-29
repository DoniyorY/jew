<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Clients;

/**
 * ClientsSearch represents the model behind the search form of `common\models\Clients`.
 */
class ClientsSearch extends Clients
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'balance', 'created', 'updated', 'status', 'client_type_id', 'is_deleted', 'deleted_time', 'deleted_user_id'], 'integer'],
            [['fullname', 'phone', 'token'], 'safe'],
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
        $query = Clients::find()->orderBy(['fullname'=>SORT_ASC]);

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
            'balance' => $this->balance,
            'created' => $this->created,
            'updated' => $this->updated,
            'status' => $this->status,
            'client_type_id' => $this->client_type_id,
            'is_deleted' => $this->is_deleted,
            'deleted_time' => $this->deleted_time,
            'deleted_user_id' => $this->deleted_user_id,
        ]);

        $query->andFilterWhere(['like', 'fullname', $this->fullname])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'token', $this->token]);

        return $dataProvider;
    }
}
