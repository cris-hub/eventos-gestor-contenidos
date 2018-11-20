<?php

namespace app\modules\recreacion\models;

/**
 * This is the ActiveQuery class for [[Headquarter]].
 *
 * @see Headquarter
 */
class HeadquarterQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Headquarter[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Headquarter|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
