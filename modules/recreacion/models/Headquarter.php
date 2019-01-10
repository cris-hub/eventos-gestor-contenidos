<?php

namespace app\modules\recreacion\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "headquarter".
 *
 * @property int $id ID
 * @property string $headquarterCode Codigo
 * @property string $name Nombre
 * @property string $description Descripcion
 * @property string $created Fecha creacion
 * @property string $created_by Creado por
 * @property string $modified_by Modificado por
 * @property string $modified fecha modificacion
 * @property string $status estado
 * @property string $emails Correos
 *
 * @property HeadquarterExperences[] $headquarterExperences
 * @property HeadquarterImages[] $headquarterImages
 * @property Lounge[] $lounges
 */
class Headquarter extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'fileBehavior' => [
                'class' => \nemmo\attachments\behaviors\FileBehavior::className()
            ],
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created', 'modified'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['modified'],
                ],
                'value' => new Expression('NOW()'),
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_by', 'modified_by'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['modified_by'],
                ],
                'value' => isset(Yii::$app->user->identity->username) ?
                Yii::$app->user->identity->username : '',
            ],
        ];
    }

    public static function tableName() {
        return 'headquarter';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name', 'description', 'emails', 'cityId'], 'required'],
            [['description', 'status', 'emails'], 'string'],
            [['cityId'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['headquarterCode'], 'string', 'max' => 10],
            [['name', 'created_by', 'modified_by'], 'string', 'max' => 50],
            [['cityId'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['cityId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'headquarterCode' => Yii::t('app', 'Codigo'),
            'name' => Yii::t('app', 'Nombre'),
            'description' => Yii::t('app', 'Descripcion'),
            'created' => Yii::t('app', 'Fecha creacion'),
            'created_by' => Yii::t('app', 'Creado por'),
            'modified_by' => Yii::t('app', 'Modificado por'),
            'modified' => Yii::t('app', 'fecha modificacion'),
            'status' => Yii::t('app', 'estado'),
            'emails' => Yii::t('app', 'Correos'),
            'cityId' => Yii::t('app', 'Ciudad'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHeadquarterExperences() {
        return $this->hasMany(HeadquarterExperences::className(), ['headquarterId' => 'id']);
    }

    public function getCity() {
        return $this->hasOne(City::className(), ['id' => 'cityId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHeadquarterImages() {
        return $this->hasMany(HeadquarterImages::className(), ['headquearterId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLounges() {
        return $this->hasMany(Lounge::className(), ['headquarterId' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return HeadquarterQuery the active query used by this AR class.
     */
    public static function find() {
        return new HeadquarterQuery(get_called_class());
    }

}
