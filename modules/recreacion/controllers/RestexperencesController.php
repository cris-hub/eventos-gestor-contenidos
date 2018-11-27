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
class RestexperencesController extends ActiveController {

    //public $modelClass = 'app\modules\recreacion\models\Hotel';
    public $modelClass = 'app\models\User';

    const MODULE = 'Recreacion';
    const ACTIVE = 'active';

    public function init() {
        parent::init();
        \Yii::$app->user->enableSession = false;
    }

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                //HttpBasicAuth::className(),
                //HttpBearerAuth::className(),
                //QueryParamAuth::className(),
                ['class' => JwtHttpBearerAuth::className(),
                    'auth' => function ($token, $authMethod) {
                        $signer = new \Lcobucci\JWT\Signer\Hmac\Sha256();
                        if ($token->verify($signer, Yii::$app->jwt->key)) {
                            $token = Yii::$app->jwt->getParser()->parse((string) $token);
                            $id = $token->getHeader('jti');
                            $auth = Authapirest::findOne(['username' => $id,
                                        'module' => self::MODULE]);
                            if (!empty($auth)) {
                                return $auth;
                            }
                        }
                        return null;
                    }
                ],
            ],
        ];

        // remove authentication filter
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);
        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
            ],
        ];
        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        $behaviors['authenticator']['except'] = ['login'];
        return$behaviors;
    }

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
    public function actionLogin() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        try {
            $model = new Authapirest();
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                date_default_timezone_set("UTC");
                date_default_timezone_set("America/Bogota");
                $signer = new Sha256();
                $expireIn = time() + 3600;
                $token = Yii::$app->jwt->getBuilder()
                        ->setIssuer('http://example.com') // Configures the issuer (iss claim)
                        ->setAudience('http://example.org') // Configures the audience (aud claim)
                        ->setId($model->username, true) // Configures the id (jti claim)
                        ->setIssuedAt(time()) //time that the token was issue (iat claim)
                        //->setNotBefore(time() + 0) //time before which the token cannot be accepted (nbf claim)
                        ->setExpiration($expireIn) // Configures the expiration time of the token (exp claim)
                        ->set('uid', 1) // Configures a new claim, called "uid"
                        ->sign($signer, Yii::$app->jwt->key) // creates a signature
                        ->getToken(); // Retrieves the generated token

                $responseData = [
                    'access_token' => "$token",
                    'expireIn' => "$expireIn",
                ];
                return $responseData;
            } else {
                throw new \yii\web\HttpException(422, json_encode($model->errors));
            }
        } catch (\Exception $exc) {
            \Yii::error(__FILE__ . ':' . __LINE__ . '{'
                    . print_r(' {ERROR [' . $exc->getMessage()
                            . '}', true) . '}', 'REST');
            return ['error' => true, 'msg' => $exc->getMessage()];
        }
    }

    public function actionGetallcities() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        try {
            return City::find()->orderby('name')->all();
        } catch (\Exception $exc) {
            \Yii::error(__FILE__ . ':' . __LINE__ . '{'
                    . print_r(' {ERROR [' . $exc->getMessage()
                            . '}', true) . '}', 'REST');
            return ['error' => true, 'msg' => Yii::$app->params['errorRest']];
        }
    }

    public function actionGetallEventTypes() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        try {
            return \app\modules\recreacion\models\EventType::find()->orderby('name')->all();
        } catch (\Exception $exc) {
            \Yii::error(__FILE__ . ':' . __LINE__ . '{'
                    . print_r(' {ERROR [' . $exc->getMessage()
                            . '}', true) . '}', 'REST');
            return ['error' => true, 'msg' => Yii::$app->params['errorRest']];
        }
    }

    public function console_log($data) {
        echo '<script>';
        echo 'console.log(' . json_encode($data) . ')';
        echo '</script>';
    }

    public function actionGetallExperences() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        try {
            $response = [];

            $experences = \app\modules\recreacion\models\Experences::find()
                    ->where('status=:status', [':status' => self::ACTIVE])
                    ->orderby('name')->all();
            foreach ($experences as $experence) {
                $model = new \stdClass();
                $model->id = $experence->id;
                $model->name = $experence->name;
                $model->eventTypeId = $experence->eventTypeId;
                $model->description = $experence->description;

                $images = [];
                foreach ($experence->files as $file) {

                    $modelImages = new \stdClass();
                    $modelImages->src = $this->getUrlImage($file->path);
                    $modelImages->alt = 'Imagen Experiencia' + $experence->name;
                    $model->imagen = $modelImages;
                }
                $response[] = $model;
            }
            return $response;
        } catch (\Exception $exc) {
            \Yii::error(__FILE__ . ':' . __LINE__ . '{'
                    . print_r(' {ERROR [' . $exc->getMessage()
                            . '}', true) . '}', 'REST');
            return ['error' => true, 'msg' => Yii::$app->params['errorRest']];
        }
    }

    public function actionGetexperencesByEventType() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        try {
            $response = ['error' => true, 'msg' => 'Parametros incorrectos'];
            if (isset($_POST['eventTypeId']) && !empty($_POST['eventTypeId']) && is_numeric($_POST['eventTypeId'])) {
                $response = [];
                $cityId = HtmlPurifier::process($_POST['eventTypeId']);
                $Experences = Experences::find()->where('eventTypeId=:eventTypeId', [':eventTypeId' => $cityId])
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
            return ['error' => true, 'msg' => Yii::$app->params['errorRest']];
        }
    }

    public function actionGetexperencesbyid() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        try {
            $response = ['error' => true, 'msg' => 'Parametros incorrectos'];
            if (isset($_POST['experenceId']) && !empty($_POST['experenceId']) && is_numeric($_POST['experenceId'])) {
                $response = [];
                $experenceId = HtmlPurifier::process($_POST['experenceId']);
                $experence = \app\modules\recreacion\models\Experences::find()->where('id=:experenceId', [':experenceId' => $experenceId])
                                ->orderby('name')->one();


                $model = new \stdClass();
                $model->id = $experence->id;
                $model->name = $experence->name;
                $model->eventTypeId = $experence->eventTypeId;
                $model->description = $experence->description;
                $response = $model;
            }
            return $response;
        } catch (\Exception $exc) {
            \Yii::error(__FILE__ . ':' . __LINE__ . '{'
                    . print_r(' {ERROR [' . $exc->getMessage()
                            . '}', true) . '}', 'REST');
            return ['error' => true, 'msg' => Yii::$app->params['errorRest']];
        }
    }

    private function getUrlImage($path) {
        $siteUrl = $this->getSiteurl();
        $tempPath = str_replace(\Yii::$app->basePath, $siteUrl . \yii\helpers\Url::base() . '/..', $path);
        return str_replace('\\', '/', $tempPath);
    }

    private function getSiteurl() {
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ||
                $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $domainName = $_SERVER['HTTP_HOST'];
        return $protocol . $domainName;
    }

}
