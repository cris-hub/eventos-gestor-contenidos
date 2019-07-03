<?php

namespace app\modules\recreacion\models;

/**
 * This is the ActiveQuery class for [[PackageAgreements]].
 *
 * @see PackageAgreements
 */
class PackageAgreementsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return PackageAgreements[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PackageAgreements|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
