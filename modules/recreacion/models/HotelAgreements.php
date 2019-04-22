<?php

namespace app\modules\recreacion\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;
use app\modules\recreacion\models\Room;

/**
 * This is the model class for table "hotel".
 *
 * @property int $id
 * @property string $hotel_code
 * @property string $hotel_chain_code
 * @property string $name
 * @property string $description
 * @property string $slug
 * @property string $cell_phone
 * @property string $address
 * @property string $phone
 * @property string $created
 * @property string $created_by
 * @property string $modified
 * @property string $modified_by
 * @property int $city_id
 * @property int $max_guests
 *
 * @property City $city
 */
class HotelAgreements extends ActiveRecord {

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
        return 'hotel_agreements';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['hotel_code', 'hotel_chain_code', 'name', 'address', 'phone', 'city_id','max_guests'], 'required'],
            [['address', 'description'], 'string'],
            [['created', 'modified', 'status'], 'safe'],
            [['city_id','max_guests'], 'integer'],
            [['name', 'slug'], 'string', 'max' => 45],
            [['cell_phone', 'phone'], 'string', 'max' => 150],
            [['created_by', 'modified_by'], 'string', 'max' => 50],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'hotel_code' => Yii::t('app', 'Código del Hotel'),
            'hotel_chain_code' => Yii::t('app', 'Código Chain del Hotel'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'slug' => Yii::t('app', 'Slug'),
            'cell_phone' => Yii::t('app', 'Cell Phone'),
            'address' => Yii::t('app', 'Address'),
            'phone' => Yii::t('app', 'Phone'),
            'status' => Yii::t('app', 'Status'),
            'created' => Yii::t('app', 'Created'),
            'created_by' => Yii::t('app', 'Created By'),
            'modified' => Yii::t('app', 'Modified'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'city_id' => Yii::t('app', 'City'),
            'max_guests' => Yii::t('app', 'Max Guests')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity() {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRooms() {
        return $this->hasMany(Room::className(), ['hotel_id' => 'id']);
    }

}
