<?php

namespace app\modules\recreacion\models;

use Yii;

/**
 * This is the model class for table "attach_file".
 *
 * @property int $id
 * @property string $name
 * @property string $model
 * @property int $itemId
 * @property string $hash
 * @property int $size
 * @property string $type
 * @property string $mime
 *
 * @property EventosExperences[] $eventosExperences
 * @property EventosHeadquarterImages[] $eventosHeadquarterImages
 * @property EventosLoungeImages[] $eventosLoungeImages
 */
class AttachFile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'attach_file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'model', 'itemId', 'hash', 'size', 'type', 'mime'], 'required'],
            [['itemId', 'size'], 'integer'],
            [['name', 'model', 'hash', 'type', 'mime'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'model' => Yii::t('app', 'Model'),
            'itemId' => Yii::t('app', 'Item ID'),
            'hash' => Yii::t('app', 'Hash'),
            'size' => Yii::t('app', 'Size'),
            'type' => Yii::t('app', 'Type'),
            'mime' => Yii::t('app', 'Mime'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventosExperences()
    {
        return $this->hasMany(EventosExperences::className(), ['imagenId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventosHeadquarterImages()
    {
        return $this->hasMany(EventosHeadquarterImages::className(), ['imagenId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventosLoungeImages()
    {
        return $this->hasMany(EventosLoungeImages::className(), ['imagenId' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return AttachFileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AttachFileQuery(get_called_class());
    }
}
