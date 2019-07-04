<?php

namespace app\modules\recreacion\models;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "lounge".
 *
 * @property int $id ID
 * @property string $loungeCode Codigo
 * @property string $name Nombre
 * @property string $description Descripcion
 * @property int $capacity Capacidad
 * @property string $created Fecha creacion
 * @property string $created_by Creado por
 * @property string $modified_by Modificado por
 * @property string $status Estado
 * @property int $headquarterId Sede
 * @property string $modified fecha modificacion
 *
 * @property Headquarter $headquarter
 * @property LoungeImages[] $loungeImages
 */
class Lounge extends \yii\db\ActiveRecord {

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
        return 'lounge';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name', 'description', 'capacity', 'headquarterId'], 'required'],
            [['description', 'status'], 'string'],
            [['capacity', 'headquarterId'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['loungeCode'], 'string', 'max' => 10],
            [['name', 'created_by', 'modified_by'], 'string', 'max' => 50],
            [['headquarterId'], 'exist', 'skipOnError' => true, 'targetClass' => Headquarter::className(), 'targetAttribute' => ['headquarterId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'loungeCode' => Yii::t('app', 'Codigo'),
            'name' => Yii::t('app', 'Nombre'),
            'description' => Yii::t('app', 'Descripcion'),
            'capacity' => Yii::t('app', 'Capacidad'),
            'created' => Yii::t('app', 'Fecha creacion'),
            'created_by' => Yii::t('app', 'Creado por'),
            'modified_by' => Yii::t('app', 'Modificado por'),
            'status' => Yii::t('app', 'Estado'),
            'headquarterId' => Yii::t('app', 'Sede'),
            'modified' => Yii::t('app', 'fecha modificacion'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHeadquarter() {
        return $this->hasOne(Headquarter::className(), ['id' => 'headquarterId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLoungeImages() {
        return $this->hasMany(LoungeImages::className(), ['loungeId' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return LoungeQuery the active query used by this AR class.
     */
    public static function find() {
        return new LoungeQuery(get_called_class());
    }

}
