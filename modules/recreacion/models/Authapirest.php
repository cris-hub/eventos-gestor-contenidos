<?php

namespace app\modules\recreacion\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "auth_api_rest".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $module
 * @property string $created
 * @property string $created_by
 * @property string $modified
 * @property string $modified_by
 */
class Authapirest extends ActiveRecord {

    public function behaviors() {
        return [
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
                'value' => isset(Yii::$app->user->identity->username)? 
                    Yii::$app->user->identity->username: '',                
            ],            
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'auth_api_rest';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['username', 'password', 'module'], 'required'],
            [['created', 'modified'], 'safe'],
            [['username', 'created_by', 'modified_by'], 'string', 'max' => 45],
            [['password', 'module'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
            'module' => Yii::t('app', 'Module'),
            'created' => Yii::t('app', 'Created'),
            'created_by' => Yii::t('app', 'Created By'),
            'modified' => Yii::t('app', 'Modified'),
            'modified_by' => Yii::t('app', 'Modified By'),
        ];
    }

    /**
     * 
     * @return type
     */
    public function login() {
        if($this->validate()){
            $user = Authapirest::find()
                ->where('username=:username and password=:password '
                        . 'and module =:module', 
                        [':username' => $this->username,
                        ':password' => md5($this->password),
                        ':module' => $this->module])->one();
            if(!empty($user)){
                return $user;
            }else{
                $this->addError('password', 'Incorrect username or password.');
                return false;
            }
        }else{
            throw new \yii\web\HttpException(422, json_encode($this->errors));
        }
    }

}
