<?php

namespace app\modules\recreacion\controllers;

use app\modules\recreacion\models\Authapirest;
use Exception;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use sizeg\jwt\JwtHttpBearerAuth;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\Cors;
use yii\web\HttpException;
use yii\rest\ActiveController;
use function GuzzleHttp\json_encode;

/**
 * ResthotelController implements the CRUD actions for hotel model.
 */
class RestauthController extends ActiveController {

    public $modelClass = 'app\models\User';

    const MODULE = 'Recreacion';
    const ACTIVE = 'active';

    public function init() {
        parent::init();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        \Yii::$app->user->enableSession = false;
    }

    public static function behaviorsUtil() {

        $behaviors['corsFilter'] = [
            'class' => Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
            ],
        ];
        $behaviors['authenticator'] = $auth;
        $behaviors['authenticator']['except'] = ['login'];
        return $behaviors;
    }

    public function behaviors() {
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                [
                    'class' => JwtHttpBearerAuth::className(),
                    'auth' => function ($token, $authMethod) {
                        $signer = new Sha256();
                        if ($token->verify($signer, Yii::$app->jwt->key)) {
                            $token = Yii::$app->jwt->getParser()->parse((string) $token);
                            $id = $token->getHeader('jti');
                            $auth = Authapirest::findOne([
                                        'username' => $id,
                                        'module' => self::MODULE
                            ]);
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
            'class' => Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
            ],
        ];
        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        $behaviors['authenticator']['except'] = ['login'];
        return $behaviors;
    }

    public function actionLogin() {
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
                throw new HttpException(422, json_encode($model->errors));
            }
        } catch (Exception $exc) {
            \Yii::error(__FILE__ . ':' . __LINE__ . '{'
                    . print_r(' {ERROR [' . $exc->getMessage()
                            . '}', true) . '}', 'REST');
            return ['error' => true, 'msg' => $exc->getMessage()];
        }
    }

}
