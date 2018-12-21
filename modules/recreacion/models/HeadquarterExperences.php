<?php

namespace app\modules\recreacion\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;
/**
 * This is the model class for table "headquarter_experences".
 *
 * @property string $created Fecha creacion
 * @property string $created_by Creado por
 * @property string $modified_by Modificado por
 * @property int $experencesId Experiencia
 * @property int $headquarterId Sede
 * @property string $status estado
 * @property int $id ID
 * @property string $modified fecha modificacion
 *
 * @property Experences $experences
 * @property Headquarter $headquarter
 */
class HeadquarterExperences extends \yii\db\ActiveRecord
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
        return 'headquarter_experences';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created', 'modified'], 'safe'],
            [['experencesId', 'headquarterId', 'status'], 'required'],
            [['experencesId', 'headquarterId', 'id'], 'integer'],
            [['status'], 'string'],
            [['created_by', 'modified_by'], 'string', 'max' => 50],
            [['experencesId', 'headquarterId', 'status'], 'unique', 'targetAttribute' => ['experencesId', 'headquarterId', 'status']],
            [['experencesId'], 'exist', 'skipOnError' => true, 'targetClass' => Experences::className(), 'targetAttribute' => ['experencesId' => 'id']],
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
            'experencesId' => Yii::t('app', 'Experiencia'),
            'headquarterId' => Yii::t('app', 'Sede'),
            'status' => Yii::t('app', 'estado'),
            'id' => Yii::t('app', 'ID'),
            'modified' => Yii::t('app', 'fecha modificacion'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExperences()
    {
        return $this->hasOne(Experences::className(), ['id' => 'experencesId']);
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
     * @return HeadquarterExperencesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new HeadquarterExperencesQuery(get_called_class());
    }
}
