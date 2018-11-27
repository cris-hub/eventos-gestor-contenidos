<?php

namespace app\modules\recreacion\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\recreacion\models\Eventos;

/**
 * EventosSearch represents the model behind the search form of `app\modules\recreacion\models\Eventos`.
 */
class EventosSearch extends Eventos
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['even_id'], 'integer'],
            [['even_name', 'even_fecha', 'even_descripcion'], 'safe'],
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
        $query = Eventos::find();

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
            'even_id' => $this->even_id,
            'even_fecha' => $this->even_fecha,
        ]);

        $query->andFilterWhere(['like', 'even_name', $this->even_name])
            ->andFilterWhere(['like', 'even_descripcion', $this->even_descripcion]);

        return $dataProvider;
    }
}
