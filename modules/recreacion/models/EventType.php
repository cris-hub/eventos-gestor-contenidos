<?php

namespace app\modules\recreacion\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;
/**
 * This is the model class for table "event_type".
 *
 * @property int $id ID
 * @property string $eventTypeCode Codigo
 * @property string $name Nombre
 * @property string $description Descripcion
 * @property string $created Fecha creacion
 * @property string $created_by Creado por
 * @property string $modified_by Modificado por
 * @property string $status estado
 * @property string $modified fecha modificacion
 *
 * @property Experences[] $experences
 */
class EventType extends \yii\db\ActiveRecord {

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
        return 'event_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name', 'description'], 'required'],
            [['description', 'status'], 'string'],
            [['created', 'modified'], 'safe'],
            [['eventTypeCode'], 'string', 'max' => 10],
            [['name', 'created_by', 'modified_by'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'eventTypeCode' => Yii::t('app', 'Codigo'),
            'name' => Yii::t('app', 'Nombre'),
            'description' => Yii::t('app', 'Descripcion'),
            'created' => Yii::t('app', 'Fecha creacion'),
            'created_by' => Yii::t('app', 'Creado por'),
            'modified_by' => Yii::t('app', 'Modificado por'),
            'status' => Yii::t('app', 'estado'),
            'modified' => Yii::t('app', 'fecha modificacion'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExperences() {
        return $this->hasMany(Experences::className(), ['eventTypeId' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return EventTypeQuery the active query used by this AR class.
     */
    public static function find() {
        return new EventTypeQuery(get_called_class());
    }

}
