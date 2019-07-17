<?php

namespace app\modules\recreacion\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\recreacion\models\HeadquarterLounge;

/**
 * HeadquarterLoungeSearch represents the model behind the search form of `app\modules\recreacion\models\HeadquarterLounge`.
 */
class HeadquarterLoungeSearch extends HeadquarterLounge
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created', 'created_by', 'modified_by'], 'safe'],
            [['loungeId', 'headquarterId'], 'integer'],
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
        $query = HeadquarterLounge::find();

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
            'created' => $this->created,
            'loungeId' => $this->loungeId,
            'headquarterId' => $this->headquarterId,
        ]);

        $query->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'modified_by', $this->modified_by]);

        return $dataProvider;
    }
}
