<?php

namespace app\modules\recreacion\models;

/**
 * This is the ActiveQuery class for [[LoungeImages]].
 *
 * @see LoungeImages
 */
class LoungeImagesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return LoungeImages[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return LoungeImages|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
