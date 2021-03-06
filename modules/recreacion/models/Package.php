<?php

namespace app\modules\recreacion\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "room".
 *
 * @property int $id
 * @property string $type_package
 * @property string $slug
 * @property string $description
 * @property int $capacity_people
 * @property string $aditional_information
 * @property string $status
 * @property string $created
 * @property string $created_by
 * @property string $modified
 * @property string $modified_by
 * @property int $hotel_id
 *
 * @property Hotel $hotel
 */
class Package extends ActiveRecord {

    public $images;

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

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'package';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['type_package', 'name', 'slug', 'description', 'capacity_people', 'hotel_id'], 'required'],
            [['description','aditional_information', 'status'], 'string'],
            [['capacity_people', 'hotel_id'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['name'], 'string', 'max' => 150],
            [['slug',  'created_by', 'modified_by'], 'string', 'max' => 45],
            [['hotel_id'], 'exist', 'skipOnError' => true, 'targetClass' => Hotel::className(), 'targetAttribute' => ['hotel_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'type_package' => Yii::t('app', 'Tipo de paquete'),
            'name' => Yii::t('app', 'Name'),
            'slug' => Yii::t('app', 'Slug'),
            'description' => Yii::t('app', 'Description'),
            'capacity_people' => Yii::t('app', 'Capacity People'),
            'aditional_information' => Yii::t('app', 'Aditional Information'),
            'status' => Yii::t('app', 'Status'),
            'created' => Yii::t('app', 'Created'),
            'created_by' => Yii::t('app', 'Created By'),
            'modified' => Yii::t('app', 'Modified'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'hotel_id' => Yii::t('app', 'Hotel'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHotel() {
        return $this->hasOne(Hotel::className(), ['id' => 'hotel_id']);
    }

}
