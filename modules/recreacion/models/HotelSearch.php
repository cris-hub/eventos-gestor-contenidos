<?php

namespace app\modules\recreacion\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\recreacion\models\Hotel;

/**
 * HotelSearch represents the model behind the search form of `app\modules\recreacion\models\Hotel`.
 */
class HotelSearch extends Hotel {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'city_id','max_guests'], 'integer'],
            [['hotel_code', 'hotel_chain_code', 'name', 'description', 'slug', 'cell_phone', 'address', 'phone',
            'status', 'created', 'created_by', 'modified', 'modified_by'],
                'safe'],
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
        $query = Hotel::find();

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
            'created' => $this->created,
            'modified' => $this->modified,
            'city_id' => $this->city_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'hotel_code', $this->hotel_code])
                ->andFilterWhere(['like', 'hotel_chain_code', $this->hotel_chain_code])
                ->andFilterWhere(['like', 'description', $this->description])
                ->andFilterWhere(['like', 'slug', $this->slug])
                ->andFilterWhere(['like', 'cell_phone', $this->cell_phone])
                ->andFilterWhere(['like', 'address', $this->address])
                ->andFilterWhere(['like', 'phone', $this->phone])
                ->andFilterWhere(['like', 'max_guests', $this->max_guests])
                ->andFilterWhere(['like', 'status', $this->status])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'modified_by', $this->modified_by]);

        return $dataProvider;
    }

}
