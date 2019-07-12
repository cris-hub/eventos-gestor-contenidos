<?php

namespace app\modules\recreacion\models;

/**
 * This is the ActiveQuery class for [[EventType]].
 *
 * @see EventType
 */
class EventTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return EventType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return EventType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
