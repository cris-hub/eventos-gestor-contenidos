<?php

namespace app\modules\recreacion\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\recreacion\models\HeadquarterExperences;
use app\modules\recreacion\models\Experences;


/**
 * HeadquarterExperencesSearch represents the model behind the search form of `app\modules\recreacion\models\HeadquarterExperences`.
 */
class HeadquarterExperencesSearch extends HeadquarterExperences
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created', 'created_by', 'modified_by'], 'safe'],
            [['experencesId','status', 'headquarterId'], 'integer'],
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
        $query = HeadquarterExperences::find();
        

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
            'experencesId' => $this->experencesId,
            'headquarterId' => $this->headquarterId,
        ]);

        $query->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'modified_by', $this->modified_by]);

        return $dataProvider;
    }
}
