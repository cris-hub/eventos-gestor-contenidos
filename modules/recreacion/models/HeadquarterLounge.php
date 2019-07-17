<?php

namespace app\modules\recreacion\models;

use Yii;

/**
 * This is the model class for table "headquarter_lounge".
 *
 * @property string $created Fecha creacion
 * @property string $created_by Creado por
 * @property string $modified_by Modificado por
 * @property int $loungeId Salon
 * @property int $headquarterId Sede
 *
 * @property Lounge $lounge
 * @property Headquarter $headquarter
 */
class HeadquarterLounge extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'headquarter_lounge';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created'], 'safe'],
            [['loungeId', 'headquarterId'], 'required'],
            [['loungeId', 'headquarterId'], 'integer'],
            [['created_by', 'modified_by'], 'string', 'max' => 50],
            [['loungeId', 'headquarterId'], 'unique', 'targetAttribute' => ['loungeId', 'headquarterId']],
            [['loungeId'], 'exist', 'skipOnError' => true, 'targetClass' => Lounge::className(), 'targetAttribute' => ['loungeId' => 'id']],
            [['headquarterId'], 'exist', 'skipOnError' => true, 'targetClass' => Headquarter::className(), 'targetAttribute' => ['headquarterId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'created' => Yii::t('app', 'Fecha creacion'),
            'created_by' => Yii::t('app', 'Creado por'),
            'modified_by' => Yii::t('app', 'Modificado por'),
            'loungeId' => Yii::t('app', 'Salon'),
            'headquarterId' => Yii::t('app', 'Sede'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLounge()
    {
        return $this->hasOne(Lounge::className(), ['id' => 'loungeId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHeadquarter()
    {
        return $this->hasOne(Headquarter::className(), ['id' => 'headquarterId']);
    }

    /**
     * {@inheritdoc}
     * @return HeadquarterLoungeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new HeadquarterLoungeQuery(get_called_class());
    }
}
