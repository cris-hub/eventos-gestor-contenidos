<?php

namespace app\modules\recreacion\controllers;

use app\modules\recreacion\models\Reservation;
use Yii;
use app\modules\recreacion\models\Hotel;
use app\modules\recreacion\models\Banner;
use app\modules\recreacion\models\HotelSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use app\modules\recreacion\models\City;

use app\modules\recreacion\models\EventType;
use app\modules\recreacion\models\Experences;


use app\modules\recreacion\models\Category;
use app\modules\recreacion\models\Room;
use yii\helpers\HtmlPurifier;
use yii\filters\auth\QueryParamAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\CompositeAuth;
use app\models\User;
use sizeg\jwt\JwtHttpBearerAuth;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use app\modules\recreacion\models\Authapirest;
use thamtech\uuid\helpers\UuidHelper;

/**
 * RestexperecesController implements the CRUD actions for hotel model.
 */
class RestexperecesController extends ActiveController
{
    
    public $modelClass = 'app\models\User';
    const MODULE = 'Recreacion';
    const ACTIVE = 'active';
    
    public function init()
    {
        parent::init();
        \Yii::$app->user->enableSession = false;
    }
    
    /**
     * 
     * @return string
     */
    // public function behaviors(){
    //     $behaviors= parent::behaviors();
        
    //     $behaviors['authenticator'] = [
    //       'class' => CompositeAuth::className(),
    //       'authMethods' => [
    //             //HttpBasicAuth::className(),
    //             //HttpBearerAuth::className(),
    //             //QueryParamAuth::className(),
    //             ['class' => JwtHttpBearerAuth::className(), 
    //                 'auth' => function ($token, $authMethod) {
    //                     $signer = new \Lcobucci\JWT\Signer\Hmac\Sha256();
    //                     if($token->verify($signer, Yii::$app->jwt->key)){
    //                         $token = Yii::$app->jwt->getParser()->parse((string) $token);
    //                         $id = $token->getHeader('jti');
    //                         $auth = Authapirest::findOne(['username' => $id, 
    //                             'module'=> self::MODULE]); 
    //                         if(!empty($auth)){
    //                             return $auth;
    //                         }
    //                     }
    //                     return null;
    //                 }
    //             ],
    //       ],
    //     ];
                
    //     // remove authentication filter
    //     $auth = $behaviors['authenticator'];
    //     unset($behaviors['authenticator']);
    //     // add CORS filter
    //     $behaviors['corsFilter'] = [
    //         'class' => \yii\filters\Cors::className(),
    //         'cors' => [
    //             'Origin' => ['*'],
    //             'Access-Control-Request-Method' => ['GET', 'POST', 'OPTIONS'],
    //             'Access-Control-Request-Headers' => ['*'],
    //         ],
    //     ];
    //     // re-add authentication filter
    //     $behaviors['authenticator'] = $auth;
    //     $behaviors['authenticator']['except'] = ['login'];

    //     return$behaviors;
    // }
    

    /**
     * Metodo que retorna todas las ciudades
     * 
     * @return json
     * @author    Alexander Arcila <alexander.arcila@ingeneo.com.co>
     * @copyright 2018 INGENEO S.A.S.
     * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
     * @version   Release: $Id$
     * @link      http://www.ingeneo.com.co
     */
    public function actionLogin(){

    }
    
    
    public function actionGetallEventTypes(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        try {
            return EventType::find()->orderby('name')->all();
        } catch (\Exception $exc) {
            \Yii::error(__FILE__ . ':' . __LINE__ . '{'
                    . print_r(' {ERROR [' . $exc->getMessage() 
                    . '}', true) . '}', 'REST');
            return ['error'=>true, 'msg'=>Yii::$app->params['errorRest']];
        }
    }
    

    public function actionGetallExperences(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        try {
            return Experences::find()->orderby('name')->all();
        } catch (\Exception $exc) {
            \Yii::error(__FILE__ . ':' . __LINE__ . '{'
                    . print_r(' {ERROR [' . $exc->getMessage() 
                    . '}', true) . '}', 'REST');
            return ['error'=>true, 'msg'=>Yii::$app->params['errorRest']];
        }
    } 

    public function actionGetexperencesByEventType(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        try {
            $response = ['error'=>true, 'msg'=>'Parametros incorrectos'];
            if(isset($_POST['eventTypeId']) && !empty($_POST['eventTypeId']) 
                    && is_numeric($_POST['eventTypeId'])){
                $response = [];
                $cityId = HtmlPurifier::process($_POST['eventTypeId']);
                $Experences = Experences::find()->where('eventTypeId=:eventTypeId', 
                        [':eventTypeId' => $cityId])
                    ->orderby('name')->all();

                foreach ($Experences as $experence) {
                    $model = new \stdClass();
                    $model->id = $experence->id;
                    $model->name = $experence->name;
                    $response[] = $model;
                }
            }
            return $response;
        } catch (\Exception $exc) {
            \Yii::error(__FILE__ . ':' . __LINE__ . '{'
                    . print_r(' {ERROR [' . $exc->getMessage() 
                    . '}', true) . '}', 'REST');
            return ['error'=>true, 'msg'=>Yii::$app->params['errorRest']];
        }
    } 
    

    

    
/*
     * Retorna el dominio y el protocolo del servidor
     * 
     * @return string
     * @author    Alexander Arcila <alexander.arcila@ingeneo.com.co>
     * @copyright 2018 INGENEO S.A.S.
     * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
     * @version   Release: $Id$
     * @link      http://www.ingeneo.com.co
     */
    private function getSiteurl() {
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || 
        $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $domainName = $_SERVER['HTTP_HOST'];
         return $protocol.$domainName;
    }
    
    /**
     * Retorna la url de la imagen 
     * 
     * @param string $path
     * 
     * @return string
     * @author    Alexander Arcila <alexander.arcila@ingeneo.com.co>
     * @copyright 2018 INGENEO S.A.S.
     * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
     * @version   Release: $Id$
     * @link      http://www.ingeneo.com.co
     */
    private function getUrlImage($path){
<<<<<<< HEAD
        $urlBlob = "https://bscolsubsidiotest.blob.core.windows.net/colsubsidioportalsalud/";
        $siteUrl = $this->getSiteurl();
        $tempPath = str_replace(\Yii::$app->basePath, $urlBlob .'/..', $path);
        return str_replace('\\', '/', $tempPath);
        }
=======
        $siteUrl = $this->getSiteurl();
        $tempPath = str_replace(\Yii::$app->basePath, 
                    $siteUrl.\yii\helpers\Url::base() .'/..', $path);
        return str_replace('\\', '/', $tempPath);
    }
>>>>>>> 5ba415694db797831d7c1c031948a084aea5606a

    public function actionGetimagebanner() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id = 1;
        $banner = Banner::find()->where('id=:id',
            [':id' => $id])->one();
        $image = [];

        if (!empty($banner)) {
            foreach ($banner->files as $file) {
                $image[] = ['url' => $this->getUrlImage($file->path)];
            }
        }
        return $image;
    }


}
