<?php

namespace app\modules\recreacion\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\recreacion\models\AttachFile;

/**
 * AttachFileSearch represents the model behind the search form of `app\modules\recreacion\models\AttachFile`.
 */
class AttachFileSearch extends AttachFile
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'itemId', 'size'], 'integer'],
            [['name', 'model', 'hash', 'type', 'mime'], 'safe'],
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
        $query = AttachFile::find();

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
            'itemId' => $this->itemId,
            'size' => $this->size,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'model', $this->model])
            ->andFilterWhere(['like', 'hash', $this->hash])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'mime', $this->mime]);

        return $dataProvider;
    }
}
