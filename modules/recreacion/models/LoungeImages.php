<?php

namespace app\modules\recreacion\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;
/**
 * This is the model class for table "lounge_images".
 *
 * @property string $created Fecha creacion
 * @property string $created_by Creado por
 * @property string $modified_by Modificado por
 * @property int $loungeId Salon
 * @property int $imagenId Imagen
 * @property string $status estado
 * @property string $modified fecha modificacion
 *
 * @property Lounge $lounge
 * @property AttachFile $imagen
 */
class LoungeImages extends \yii\db\ActiveRecord {

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
        return 'lounge_images';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['created', 'modified'], 'safe'],
            [['loungeId', 'imagenId'], 'required'],
            [['loungeId', 'imagenId'], 'integer'],
            [['status'], 'string'],
            [['created_by', 'modified_by'], 'string', 'max' => 50],
            [['loungeId'], 'exist', 'skipOnError' => true, 'targetClass' => Lounge::className(), 'targetAttribute' => ['loungeId' => 'id']],
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
            'loungeId' => Yii::t('app', 'Salon'),
            'imagenId' => Yii::t('app', 'Imagen'),
            'status' => Yii::t('app', 'estado'),
            'modified' => Yii::t('app', 'fecha modificacion'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLounge() {
        return $this->hasOne(Lounge::className(), ['id' => 'loungeId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImagen() {
        return $this->hasOne(AttachFile::className(), ['id' => 'imagenId']);
    }

    /**
     * {@inheritdoc}
     * @return LoungeImagesQuery the active query used by this AR class.
     */
    public static function find() {
        return new LoungeImagesQuery(get_called_class());
    }

}
