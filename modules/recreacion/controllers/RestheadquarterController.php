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
class RestheadquarterController extends ActiveController {

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

    public function actionGetallHeadquarter() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        try {
            return \app\modules\recreacion\models\Headquarter::find()->orderby('name')->all();
        } catch (\Exception $exc) {
            \Yii::error(__FILE__ . ':' . __LINE__ . '{'
                    . print_r(' {ERROR [' . $exc->getMessage()
                            . '}', true) . '}', 'REST');
            return ['error' => true, 'msg' => Yii::$app->params['errorRest']];
        }
    }

    public function actionGetlistheadquarterbycapacityloungeandexperenceid() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        try {
            $response = ['error' => true, 'msg' => 'Parametros incorrectos'];
            if (
                    (isset($_POST['capacity']) && !empty($_POST['capacity']) && is_numeric($_POST['capacity'])) &&
                    (isset($_POST['city']) && !empty($_POST['city']) ) &&
                    (isset($_POST['experenceId']) && !empty($_POST['experenceId']) && is_numeric($_POST['experenceId']))) {
                $response = [];
                $capacity = HtmlPurifier::process($_POST['capacity']);
                $experence = HtmlPurifier::process($_POST['experenceId']);
                $city = HtmlPurifier::process($_POST['city']);

                $headquarters = \app\modules\recreacion\models\Headquarter
                                ::find()
                                ->join(
                                        'INNER JOIN', 'headquarter_experences', 'headquarter_experences.headquarterId = headquarter.id '
                                )->join(
                                        'INNER JOIN', 'lounge', 'lounge.headquarterId = headquarter.id ')
                                ->where(
                                        'lounge.capacity>=:capacity '
                                        . 'and '
                                        . 'headquarter_experences.experencesId=:experence '
                                        . 'and '
                                        . 'headquarter.cityId=:city '
                                        . 'and '
                                        . 'headquarter.status=:status '
                                        , [
                                    ':capacity' => $capacity,
                                    ':experence' => $experence,
                                    ':city' => $city,
                                    ':status' => self::ACTIVE
                                ])
                                ->orderby('headquarter.name')->all();

                foreach ($headquarters as $headquarter) {
                    $model = new \stdClass();
                    $model->id = $headquarter->id;
                    $model->name = $headquarter->name;
                    $model->description = $headquarter->description;
                    $model->emails = $headquarter->emails;


                    $images = [];
                    foreach ($headquarter->files as $file) {
                        $modelImages = new \stdClass();
                        $modelImages->src = $this->getUrlImage($file->path);
                        $modelImages->alt = 'Imagen sede' + $headquarter->name;

                        $image = new \stdClass();
                        $image->imagen = $modelImages;
                        $images[] = $image;
                        $model->images = $images;
                    }


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
