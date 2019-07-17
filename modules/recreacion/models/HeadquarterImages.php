<?php

namespace app\modules\recreacion\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;
/**
 * This is the model class for table "headquarter_images".
 *
 * @property string $created Fecha creacion
 * @property string $created_by Creado por
 * @property string $modified_by Modificado por
 * @property int $headquearterId Sede
 * @property int $imagenId Imagen
 * @property string $status
 *
 * @property Headquarter $headquearter
 * @property AttachFile $imagen
 */
class HeadquarterImages extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'headquarter_images';
    }

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

    public function rules() {
        return [
            [['created'], 'safe'],
            [['headquearterId', 'imagenId'], 'required'],
            [['headquearterId', 'imagenId'], 'integer'],
            [['status'], 'string'],
            [['created_by', 'modified_by'], 'string', 'max' => 50],
            [['headquearterId'], 'exist', 'skipOnError' => true, 'targetClass' => Headquarter::className(), 'targetAttribute' => ['headquearterId' => 'id']],
            [['imagenId'], 'exist', 'skipOnError' => true, 'targetClass' => AttachFile::className(), 'targetAttribute' => ['imagenId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'created' => Yii::t('app', 'Fecha creacion'),
            'created_by' => Yii::t('app', 'Creado por'),
            'modified_by' => Yii::t('app', 'Modificado por'),
            'headquearterId' => Yii::t('app', 'Sede'),
            'imagenId' => Yii::t('app', 'Imagen'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHeadquearter() {
        return $this->hasOne(Headquarter::className(), ['id' => 'headquearterId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImagen() {
        return $this->hasOne(AttachFile::className(), ['id' => 'imagenId']);
    }

    /**
     * {@inheritdoc}
     * @return HeadquarterImagesQuery the active query used by this AR class.
     */
    public static function find() {
        return new HeadquarterImagesQuery(get_called_class());
    }

}
