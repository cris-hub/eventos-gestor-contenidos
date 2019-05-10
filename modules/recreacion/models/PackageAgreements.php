<?php

namespace app\modules\recreacion\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;
/**
 * This is the model class for table "package_agreements".
 *
 * @property int $id
 * @property string $type_package
 * @property string $name
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
 * @property HotelAgreements $hotel
 */
class PackageAgreements extends \yii\db\ActiveRecord
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
        return 'package_agreements';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type_package', 'name', 'description', 'hotel_id'], 'required'],
            [['description', 'aditional_information', 'status'], 'string'],
            [['capacity_people', 'hotel_id'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['type_package'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 150],
            [['slug', 'created_by', 'modified_by'], 'string', 'max' => 45],
            [['type_package'], 'unique'],
            [['hotel_id'], 'exist', 'skipOnError' => true, 'targetClass' => HotelAgreements::className(), 'targetAttribute' => ['hotel_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type_package' => Yii::t('app', 'Type Package'),
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
            'hotel_id' => Yii::t('app', 'Hotel ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHotel()
    {
        return $this->hasOne(HotelAgreements::className(), ['id' => 'hotel_id']);
    }

    /**
     * {@inheritdoc}
     * @return PackageAgreementsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PackageAgreementsQuery(get_called_class());
    }
}
