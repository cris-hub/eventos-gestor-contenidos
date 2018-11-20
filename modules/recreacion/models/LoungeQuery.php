<?php

namespace app\modules\recreacion\models;

/**
 * This is the ActiveQuery class for [[Lounge]].
 *
 * @see Lounge
 */
class LoungeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Lounge[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Lounge|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
