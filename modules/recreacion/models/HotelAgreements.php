<?php

namespace app\modules\recreacion\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;
/**
 * This is the model class for table "hotel_agreements".
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
 * @property string $status
 * @property string $created
 * @property string $created_by
 * @property string $modified
 * @property string $modified_by
 * @property int $city_id
 * @property int $max_guests
 * @property string $ubicacion
 *
 * @property City $city
 * @property PackageAgreements[] $packageAgreements
 * @property RoomAgreements[] $roomAgreements
 */
class HotelAgreements extends \yii\db\ActiveRecord
{

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
    public static function tableName()
    {
        return 'hotel_agreements';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hotel_code', 'name', 'address', 'status', 'city_id'], 'required'],
            [['description', 'address', 'status', 'ubicacion'], 'string'],
            [['created', 'modified'], 'safe'],
            [['city_id', 'max_guests'], 'integer'],
            [['hotel_code', 'hotel_chain_code'], 'string', 'max' => 10],
            [['name', 'slug'], 'string', 'max' => 45],
            [['cell_phone', 'phone'], 'string', 'max' => 150],
            [['created_by', 'modified_by'], 'string', 'max' => 50],
            [['hotel_code'], 'unique'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'hotel_code' => Yii::t('app', 'Hotel Code'),
            'hotel_chain_code' => Yii::t('app', 'Hotel Chain Code'),
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
            'city_id' => Yii::t('app', 'City ID'),
            'max_guests' => Yii::t('app', 'Max Guests'),
            'ubicacion' => Yii::t('app', 'Ubicacion'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackageAgreements()
    {
        return $this->hasMany(PackageAgreements::className(), ['hotel_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoomAgreements()
    {
        return $this->hasMany(RoomAgreements::className(), ['hotel_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return HotelAgreementsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new HotelAgreementsQuery(get_called_class());
    }
}
