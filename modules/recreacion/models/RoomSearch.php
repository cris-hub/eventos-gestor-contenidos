<?php

namespace app\modules\recreacion\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\recreacion\models\Room;

/**
 * RoomSearch represents the model behind the search form of `app\modules\recreacion\models\Room`.
 */
class RoomSearch extends Room {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'capacity_people', 'hotel_id'], 'integer'],
            [['name', 'type_package', 'type_room','slug', 'description', 'aditional_information',
            'status', 'created', 'created_by', 'modified', 'modified_by'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios() {
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
    public function search($params) {
        $query = Room::find();

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
            'capacity_people' => $this->capacity_people,
            'created' => $this->created,
            'modified' => $this->modified,
            'hotel_id' => $this->hotel_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'type_package', $this->type_package])
                ->andFilterWhere(['like', 'type_room', $this->type_room])
                ->andFilterWhere(['like', 'slug', $this->slug])
                ->andFilterWhere(['like', 'description', $this->description])
                ->andFilterWhere(['like', 'aditional_information', $this->aditional_information])
                ->andFilterWhere(['like', 'status', $this->status])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'modified_by', $this->modified_by]);

        return $dataProvider;
    }

}
