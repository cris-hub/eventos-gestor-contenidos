<?php

namespace app\modules\recreacion\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "experences".
 *
 * @property int $id ID
 * @property string $experencesCode Codigo
 * @property string $name Nombre
 * @property string $description Descripcion
 * @property string $created Fecha creacion
 * @property string $modified fecha modificacion
 * @property string $created_by Creado por
 * @property string $modified_by Modificado por
 * @property int $imagenId Imagen
 * @property int $eventTypeId Tipo evento
 * @property string $status estado
 *
 * @property AttachFile $imagen
 * @property EventType $eventType
 * @property HeadquarterExperences[] $headquarterExperences
 */
class Experences extends \yii\db\ActiveRecord
{
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
    public static function tableName()
    {
        return 'experences';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['description', 'status'], 'string'],
            [['created', 'modified'], 'safe'],
            [['imagenId', 'eventTypeId'], 'integer'],
            [['experencesCode'], 'string', 'max' => 10],
            [['name', 'created_by', 'modified_by'], 'string', 'max' => 50],
            [['imagenId'], 'exist', 'skipOnError' => true, 'targetClass' => AttachFile::className(), 'targetAttribute' => ['imagenId' => 'id']],
            [['eventTypeId'], 'exist', 'skipOnError' => true, 'targetClass' => EventType::className(), 'targetAttribute' => ['eventTypeId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'experencesCode' => Yii::t('app', 'Codigo'),
            'name' => Yii::t('app', 'Nombre'),
            'description' => Yii::t('app', 'Descripcion'),
            'created' => Yii::t('app', 'Fecha creacion'),
            'modified' => Yii::t('app', 'fecha modificacion'),
            'created_by' => Yii::t('app', 'Creado por'),
            'modified_by' => Yii::t('app', 'Modificado por'),
            'imagenId' => Yii::t('app', 'Imagen'),
            'eventTypeId' => Yii::t('app', 'Tipo evento'),
            'status' => Yii::t('app', 'estado'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImagen()
    {
        return $this->hasOne(AttachFile::className(), ['id' => 'imagenId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventType()
    {
        return $this->hasOne(EventType::className(), ['id' => 'eventTypeId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHeadquarterExperences()
    {
        return $this->hasMany(HeadquarterExperences::className(), ['experencesId' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return ExperencesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ExperencesQuery(get_called_class());
    }
}
