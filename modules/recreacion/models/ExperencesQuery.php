<?php

namespace app\modules\recreacion\models;

/**
 * This is the ActiveQuery class for [[Experences]].
 *
 * @see Experences
 */
class ExperencesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Experences[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Experences|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
