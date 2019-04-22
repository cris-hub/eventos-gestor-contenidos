<?php

namespace app\modules\recreacion\controllers;

use app\modules\recreacion\models\Reservation;
use Yii;
use app\modules\recreacion\models\Hotel;
use app\modules\recreacion\models\Banner;
use yii\rest\ActiveController;
use app\modules\recreacion\models\City;
use app\modules\recreacion\models\Category;
use app\modules\recreacion\models\Room;
use yii\helpers\HtmlPurifier;
use yii\filters\auth\CompositeAuth;
use sizeg\jwt\JwtHttpBearerAuth;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use app\modules\recreacion\models\Authapirest;
use thamtech\uuid\helpers\UuidHelper;


/**
 * ResthotelController implements the CRUD actions for hotel model.
 */
class ResthotelagreementsController extends ActiveController
{

    //public $modelClass = 'app\modules\recreacion\models\Hotel';
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
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                //HttpBasicAuth::className(),
                //HttpBearerAuth::className(),
                //QueryParamAuth::className(),
                [
                    'class' => JwtHttpBearerAuth::className(),
                    'auth' => function ($token, $authMethod) {
                        $signer = new \Lcobucci\JWT\Signer\Hmac\Sha256();
                        if ($token->verify($signer, Yii::$app->jwt->key)) {
                            $token = Yii::$app->jwt->getParser()->parse((string)$token);
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
        return $behaviors;
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
    public function actionLogin()
    {
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
    public function actionGetallcities()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        try {
            return City::find()->orderby('name')->all();
        } catch (\Exception $exc) {
            \Yii::error(__FILE__ . ':' . __LINE__ . '{'
                . print_r(' {ERROR [' . $exc->getMessage()
                . '}', true) . '}', 'REST');
            return ['error' => true, 'exeptions' => $exc->getMessage(), 'msg' => Yii::$app->params['errorRest']];
        }
    }



    /**
     * Metodo que retorna el listado de los hoteles por ciudad
     * 
     * @param integer $cityId Id de la ciudad
     * 
     * @return json
     * @author    Alexander Arcila <alexander.arcila@ingeneo.com.co>
     * @copyright 2018 INGENEO S.A.S.
     * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
     * @version   Release: $Id$
     * @link      http://www.ingeneo.com.co
     */
    public function actionGetlisthotelsbycity()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        try {
            $response = ['error' => true, 'msg' => 'Parametros incorrectos'];
            if (isset($_POST['cityId']) && !empty($_POST['cityId']) && is_numeric($_POST['cityId'])) {
                $response = [];
                $cityId = HtmlPurifier::process($_POST['cityId']);
                $hotels = Hotel::find()->where('city_id=:cityId and status=:status', [':cityId' => $cityId, ':status' => self::ACTIVE])
                    ->orderby('name')->all();

                foreach ($hotels as $hotel) {
                    $model = new \stdClass();
                    $model->id = $hotel->id;
                    $model->name = $hotel->name;
                    $model->max_guests = $hotel->max_guests;
                    $response[] = $model;
                }
            }
            \Yii::$app->response->statusCode = 200;
            return $response;
        } catch (\Exception $exc) {
            \Yii::error(__FILE__ . ':' . __LINE__ . '{'
                . print_r(' {ERROR [' . $exc->getMessage()
                . '}', true) . '}', 'REST');
            return ['error' => true, 'exeptions' => $exc->getMessage(), 'msg' => Yii::$app->params['errorRest']];
        }
    }

    /**
     * Metodo que permite consultar los hoteles por ciudad
     * 
     * @param integer $cityId Id de la ciudad
     * 
     * @return json
     * @author    Alexander Arcila <alexander.arcila@ingeneo.com.co>
     * @copyright 2018 INGENEO S.A.S.
     * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
     * @version   Release: $Id$
     * @link      http://www.ingeneo.com.co
     */
    public function actionGethotelsbycity()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        try {
            $response = ['error' => true, 'msg' => 'Parametros incorrectos'];
            if (isset($_POST['cityId']) && !empty($_POST['cityId']) && is_numeric($_POST['cityId'])) {
                $response = [];
                $cityId = HtmlPurifier::process($_POST['cityId']);
                $hotels = Hotel::find()->where('city_id=:cityId and status=:status', [':cityId' => $cityId, ':status' => self::ACTIVE])
                    ->orderby('name')->all();

                foreach ($hotels as $hotel) {
                    $model = new \stdClass();
                    $model->id = $hotel->id;
                    $model->name = $hotel->name;
                    $model->description = $hotel->description;
                    $model->slug = $hotel->slug;
                    $model->cell_phone = $hotel->cell_phone;
                    $model->address = $hotel->address;
                    $model->phone = $hotel->phone;
                    $images = [];
                    foreach ($hotel->files as $file) {
                        $images[] = ['url' => $this->getUrlImage($file->path)];
                    }
                    $model->images = $images;
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

    /**
     * Metodo que permite consultar los hoteles por id
     * 
     * @param integer $id Id del hotel
     *
     * @return json
     * @author    Alexander Arcila <alexander.arcila@ingeneo.com.co>
     * @copyright 2018 INGENEO S.A.S.
     * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
     * @version   Release: $Id$
     * @link      http://www.ingeneo.com.co
     */
    public function actionGethotelsbyid()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        try {
            $response = ['error' => true, 'msg' => 'Parametros incorrectos'];
            if (isset($_POST['id']) && !empty($_POST['id']) && is_numeric($_POST['id'])) {
                $response = [];
                $id = HtmlPurifier::process($_POST['id']);
                $hotel = Hotel::find()->where('id=:id and status=:status', [':id' => $id, ':status' => self::ACTIVE])->one();

                if (!empty($hotel)) {
                    $model = new \stdClass();
                    $model->id = $hotel->id;
                    $model->name = $hotel->name;
                    $model->description = $hotel->description;
                    $model->slug = $hotel->slug;
                    $model->cell_phone = $hotel->cell_phone;
                    $model->address = $hotel->address;
                    $model->phone = $hotel->phone;
                    $images = [];
                    foreach ($hotel->files as $file) {
                        $images[] = ['url' => $this->getUrlImage($file->path)];
                    }
                    $model->images = $images;
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

    /**
     * Metodo que permite consultar los hoteles por todos los parametros
     *
     * @param string  $json  Json con los parametros cityId,checkIn,checkOut,guests,hotelId
     *
     * @return json
     * @author    Alexander Arcila <alexander.arcila@ingeneo.com.co>
     * @copyright 2018 INGENEO S.A.S.
     * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
     * @version   Release: $Id$
     * @link      http://www.ingeneo.com.co
     */
    public function actionSearchhotels()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        try {
            $response = ['error' => true, 'msg' => 'Parametros incorrectos'];
            if (isset($_POST['json']) && !empty($_POST['json'])) {
                $post = json_decode($_POST['json']);
                if (isset($post->cityId) && !empty($post->cityId) && isset($post->checkIn) && !empty($post->checkIn) && isset($post->checkOut) && !empty($post->checkOut) && isset($post->guests) && !empty($post->guests) && isset($post->hotelId)) {
                    $response = [];
                    $cityId = HtmlPurifier::process($post->cityId);
                    $hotelId = HtmlPurifier::process($post->hotelId);
                    if (!empty($hotelId)) {
                        $hotels = Hotel::find()->where('id=:hotelId and status=:status', [
                            ':hotelId' => $hotelId,
                            ':status' => self::ACTIVE
                        ])
                            ->orderby('name')->all();
                    } else {
                        $hotels = Hotel::find()->where('city_id=:cityId and status=:status', [
                            ':cityId' => $cityId,
                            ':status' => self::ACTIVE
                        ])
                            ->orderby('name')->all();
                    }

                    foreach ($hotels as $hotel) {
                        $model = new \stdClass();
                        $model->id = $hotel->id;
                        $model->hotel_code = $hotel->hotel_code;
                        $model->hotel_chain_code = $hotel->hotel_chain_code;
                        $model->name = $hotel->name;
                        $model->description = $hotel->description;
                        $model->slug = $hotel->slug;
                        $model->cell_phone = $hotel->cell_phone;
                        $model->address = $hotel->address;
                        $model->phone = $hotel->phone;
                        $images = [];
                        foreach ($hotel->files as $file) {
                            $images[] = ['url' => $this->getUrlImage($file->path)];
                        }
                        $model->images = $images;
                        $response[] = $model;
                    }
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

    /**
     * Retorna el listado de habitaciones por hotel
     *
     * @param string  $json  Json con los parametros hotelId
     *
     * @return json
     * @author    Alexander Arcila <alexander.arcila@ingeneo.com.co>
     * @copyright 2018 INGENEO S.A.S.
     * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
     * @version   Release: $Id$
     * @link      http://www.ingeneo.com.co
     */


    public function actionGetroombyhotelid()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        try {
            $response = ['error' => true, 'msg' => 'Parametros incorrectos'];
            if (isset($_POST['json']) && !empty($_POST['json'])) {
                $response = [];
                $post = json_decode($_POST['json']);
                if (isset($post->cityId) && !empty($post->cityId) && isset($post->checkIn) && !empty($post->checkIn) && isset($post->checkOut) && !empty($post->checkOut) && isset($post->guests) && !empty($post->guests) && isset($post->hotelId)) {
                    $hotelId = HtmlPurifier::process($post->hotelId);
                    $guests = HtmlPurifier::process($post->guests);
                    $checkIn = HtmlPurifier::process($post->checkIn);
                    $checkOut = HtmlPurifier::process($post->checkIn);

                    $guestsIn = [];
                    for ($i = $guests; $i < ($guests + 2); $i++) {
                        $guestsIn[] = $i;
                    }

                    $rooms = Room::find()->where('hotel_id=:hotelId '
                        . 'and status=:status', [
                        ':hotelId' => $hotelId,
                        ':status' => self::ACTIVE
                    ])
                        ->andWhere(['in', 'capacity_people', $guestsIn])
                        ->orderby('name')->all();

                    foreach ($rooms as $room) {
                        $model = new \stdClass();
                        $model->id = $room->id;
                        $model->type_room = $room->type_room;
                        $model->name = $room->name;
                        $model->description = $room->description;
                        $model->slug = $room->slug;
                        $model->capacity_people = $room->capacity_people;
                        $model->aditional_information = $room->aditional_information;
                        $model->type_package = $room->type_package;
                        $model->guestsIn = $guestsIn;
                        $iparr = split(",", $model->type_package);
                        $sizeArray = sizeof($iparr);
                        if ($sizeArray > 0) {
                            foreach ($iparr as $v) {
                                $packages = \app\modules\recreacion\models\Package::find()->where("type_package LIKE '%' :id '%'  AND status=:status  ", [':id' => $v, ':status' => self::ACTIVE])->andWhere(['in', 'capacity_people', $guestsIn])->orderby('name')->all();
                                if (isset($packages)) {
                                    $model->type_package = $room->type_package;
                                    $model->guestsIn = $guestsIn;
                                    $model->packageSize = sizeof($packages);
                                    foreach ($packages as $key => $packa) {
                                        $packageToSend = new \stdClass();
                                        $temp = $packa;

                                        $images = [];
                                        foreach ($temp->files as $file) {
                                            $images[] = ['url' => $this->getUrlImage($file->path)];
                                        }
                                        $packageToSend->roomPackage = $packages[$key];
                                        $packageToSend->images = $images;
                                        $model->package = $packageToSend;

                                        $images = [];
                                        foreach ($room->files as $file) {
                                            $images[] = ['url' => $this->getUrlImage($file->path)];
                                        }


                                        $model->images = $images;

                                        $model->packageFound[] = $packa->type_package;
                                        $modelreturn = new \stdClass();
                                        $modelreturn = clone ($model);
                                        array_push($response, $modelreturn);
                                    }
                                }
                            }
                        } else {
                            $package = new \stdClass();

                            $package->roomPackage = \app\modules\recreacion\models\Package::find()->where('type_package=:id and status=:status', [':id' => $model->type_package, ':status' => self::ACTIVE])->one();

                            if (isset($package->roomPackage)) {
                                $temp = $package->roomPackage;
                                $images = [];
                                foreach ($temp->files as $file) {
                                    $images[] = ['url' => $this->getUrlImage($file->path)];
                                }
                                $package->images = $images;
                                $model->package = $package;
                                $images = [];
                                foreach ($room->files as $file) {
                                    $images[] = ['url' => $this->getUrlImage($file->path)];
                                }
                                $model->images = $images;
                                $response[] = $model;
                            }

                        }

                    }
                }
            }


            return $response;
        } catch (\Exception $exc) {
            \Yii::error(__FILE__ . ':' . __LINE__ . '{'
                . print_r(' {ERROR [' . $exc->getMessage()
                . '}', true) . '}', 'REST');
            return ['error' => true, 'info' => $exc->getMessage(), 'msg' => Yii::$app->params['errorRest']];
        }
    }

    public function actionGetpackages()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        try {
            $response = ['error' => true, 'msg' => 'Parametros incorrectos'];
            if (isset($_POST['json']) && !empty($_POST['json'])) {
                $response = [];
                $post = json_decode($_POST['json']);
                if (isset($post->cityId) && !empty($post->cityId) && isset($post->checkIn) && !empty($post->checkIn) && isset($post->checkOut) && !empty($post->checkOut) && isset($post->guests) && !empty($post->guests) && isset($post->hotelId)) {
                    $hotelId = HtmlPurifier::process($post->hotelId);
                    $guests = HtmlPurifier::process($post->guests);
                    $checkIn = HtmlPurifier::process($post->checkIn);
                    $checkOut = HtmlPurifier::process($post->checkIn);

                    $guestsIn = [];
                    for ($i = $guests; $i < ($guests + 2); $i++) {
                        $guestsIn[] = $i;
                    }
                    $model = new \stdClass();

                    $packages = \app\modules\recreacion\models\Package::find()->where("status=:status", [':status' => self::ACTIVE])->groupby('name')->orderby('name')->all();
                    if (isset($packages)) {
                        $model->guestsIn = $guestsIn;
                        $model->packageSize = sizeof($packages);
                        foreach ($packages as $key => $packa) {
                            $packageToSend = new \stdClass();
                            $temp = $packa;

                            $images = [];
                            foreach ($temp->files as $file) {
                                $images[] = ['url' => $this->getUrlImage($file->path)];
                            }
                            $packageToSend->roomPackage = $packages[$key];
                            $packageToSend->images = $images;
                            $model->package = $packageToSend;


                            $model->images = $images;

                            $model->packageFound[] = $packa->type_package;
                            $modelreturn = new \stdClass();
                            $modelreturn = clone ($model);
                            array_push($response, $modelreturn);
                        }
                    }
                }
            }




            return $response;
        } catch (\Exception $exc) {
            \Yii::error(__FILE__ . ':' . __LINE__ . '{'
                . print_r(' {ERROR [' . $exc->getMessage()
                . '}', true) . '}', 'REST');
            return ['error' => true, 'info' => $exc->getMessage(), 'msg' => Yii::$app->params['errorRest']];
        }
    }

    public function actionGetroombynamepackage()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        try {
            $response = ['error' => true, 'msg' => 'Parametros incorrectos'];
            if (isset($_POST['json']) && !empty($_POST['json'])) {
                $response = [];
                $post = json_decode($_POST['json']);
                if (isset($post->cityId) && !empty($post->cityId) && isset($post->checkIn) && !empty($post->checkIn) && isset($post->checkOut) && !empty($post->checkOut) && isset($post->guests) && !empty($post->guests) && isset($post->hotelId)) {
                    $hotelId = HtmlPurifier::process($post->hotelId);
                    $guests = HtmlPurifier::process($post->guests);
                    $checkIn = HtmlPurifier::process($post->checkIn);
                    $checkOut = HtmlPurifier::process($post->checkIn);
                    $checkOut = HtmlPurifier::process($post->checkIn );
                    $packageName = HtmlPurifier::process($post->packageName);

                    $guestsIn = [];
                    for ($i = $guests; $i < ($guests + 2); $i++) {
                        $guestsIn[] = $i;
                    }

                    $rooms = Room::find()->where('hotel_id=:hotelId '
                        . 'and status=:status', [
                        ':hotelId' => $hotelId,
                        ':status' => self::ACTIVE
                    ])
                        ->andWhere(['in', 'capacity_people', $guestsIn])
                        ->orderby('name')->all();

                    foreach ($rooms as $room) {
                        $model = new \stdClass();
                        $model->id = $room->id;
                        $model->type_room = $room->type_room;
                        $model->name = $room->name;
                        $model->description = $room->description;
                        $model->slug = $room->slug;
                        $model->capacity_people = $room->capacity_people;
                        $model->aditional_information = $room->aditional_information;
                        $model->type_package = $room->type_package;
                        $model->guestsIn = $guestsIn;
                        $iparr = split(",", $model->type_package);
                        $sizeArray = sizeof($iparr);
                        if ($sizeArray > 0) {
                            foreach ($iparr as $v) {
                                $packages = \app\modules\recreacion\models\Package::find()->where("type_package LIKE '%' :id '%'  AND status=:status AND name=:packageName ", [':id' => $v, ':status' => self::ACTIVE, 'packageName' => $packageName])->orderby('name')->all();
                                if (isset($packages)) {
                                    $model->type_package = $room->type_package;
                                    $model->guestsIn = $guestsIn;
                                    $model->packageSize = sizeof($packages);
                                    foreach ($packages as $key => $packa) {
                                        $packageToSend = new \stdClass();
                                        $temp = $packa;

                                        $images = [];
                                        foreach ($temp->files as $file) {
                                            $images[] = ['url' => $this->getUrlImage($file->path)];
                                        }
                                        $packageToSend->roomPackage = $packages[$key];
                                        $packageToSend->images = $images;
                                        $model->package = $packageToSend;

                                        $images = [];
                                        foreach ($room->files as $file) {
                                            $images[] = ['url' => $this->getUrlImage($file->path)];
                                        }


                                        $model->images = $images;

                                        $model->packageFound[] = $packa->type_package;
                                        $modelreturn = new \stdClass();
                                        $modelreturn = clone ($model);
                                        array_push($response, $modelreturn);
                                    }
                                }
                            }
                        } else {
                            $package = new \stdClass();

                            $package->roomPackage = \app\modules\recreacion\models\Package::find()->where('type_package=:id and status=:status', [':id' => $model->type_package, ':status' => self::ACTIVE])->one();

                            if (isset($package->roomPackage)) {
                                $temp = $package->roomPackage;
                                $images = [];
                                foreach ($temp->files as $file) {
                                    $images[] = ['url' => $this->getUrlImage($file->path)];
                                }
                                $package->images = $images;
                                $model->package = $package;
                                $images = [];
                                foreach ($room->files as $file) {
                                    $images[] = ['url' => $this->getUrlImage($file->path)];
                                }
                                $model->images = $images;
                                $response[] = $model;
                            }

                        }

                    }
                }
            }


            return $response;
        } catch (\Exception $exc) {
            \Yii::error(__FILE__ . ':' . __LINE__ . '{'
                . print_r(' {ERROR [' . $exc->getMessage()
                . '}', true) . '}', 'REST');
            return ['error' => true, 'info' => $exc->getMessage(), 'msg' => Yii::$app->params['errorRest']];
        }
    }




    /**
     * Retorna el listado de habitaciones por hotel
     *
     * @param string  $json  Json con los parametros hotelId
     *
     * @return json
     * @author    Alexander Arcila <alexander.arcila@ingeneo.com.co>
     * @copyright 2018 INGENEO S.A.S.
     * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
     * @version   Release: $Id$
     * @link      http://www.ingeneo.com.co
     */
    public function actionGetroombyid()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        try {
            $response = [];
            if (isset($_POST['id']) && !empty($_POST['id']) && is_numeric($_POST['id'])) {
                $response = [];
                $id = HtmlPurifier::process($_POST['id']);
                $room = Room::find()->where('id=:id and status=:status', [':id' => $id, ':status' => self::ACTIVE])->one();

                if (!empty($room)) {
                    $model = new \stdClass();
                    $model->id = $room->id;
                    $model->name = $room->name;
                    $model->description = $room->description;
                    $model->slug = $room->slug;
                    $model->number_adults = $room->number_adults;
                    $model->number_children = $room->number_children;
                    $model->aditional_information = $room->aditional_information;
                    $images = [];
                    foreach ($room->files as $file) {
                        $images[] = ['url' => $this->getUrlImage($file->path)];
                    }
                    $model->images = $images;
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

    /**
     * Retorna el dominio y el protocolo del servidor
     *
     * @return string
     * @author    Alexander Arcila <alexander.arcila@ingeneo.com.co>
     * @copyright 2018 INGENEO S.A.S.
     * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
     * @version   Release: $Id$
     * @link      http://www.ingeneo.com.co
     */
    private function getSiteurl()
    {
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ||
            $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $domainName = $_SERVER['HTTP_HOST'];
        return $protocol . $domainName;
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
    private function getUrlImage($path)
    {
        $urlBlob = "https://bscolsubsidiotest.blob.core.windows.net/colsubsidioportalsalud/";
        $siteUrl = $this->getSiteurl();
        $tempPath = str_replace(\Yii::$app->basePath, $urlBlob . '/..', $path);
        return str_replace('\\', '/', $tempPath);
    }

    public function actionGetimagebanner()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id = 1;
        $banner = Banner::find()->where('id=:id', [':id' => $id])->one();
        $image = [];

        if (!empty($banner)) {
            foreach ($banner->files as $file) {
                $image[] = ['url' => $this->getUrlImage($file->path)];
            }
        }
        return $image;
    }

    public function actionApplyPayment()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (isset($_POST['identificador_proceso'], $_POST['status']) && !empty($_POST['identificador_proceso']) && !empty($_POST['status'])) {
            $reserva = Reservation::find()
                ->where([
                    'identificador_proceso' => $_POST['identificador_proceso']
                ])
                ->one();
            if ($reserva !== null && $reserva->validate()) {
                $reserva->status = $_POST['status'];
                $reserva->fecha_confirmacion = date('Y-m-d H:i:s');
                if ($reserva->save()) {
                    return ['message' => true];
                }
            }
        }
        return ['message' => 'Error al tratar de guardar los datos'];
    }

    public function actionReservationPayment()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (isset($_POST['hotel_id']) && !empty($_POST['hotel_id'])) {
            $data = $_POST;
            $reserva = new Reservation();
            $reserva->hotel_id = $data['hotel_id'];
            $reserva->city_id = $data['city_id'];
            $reserva->room_id = $data['room_id'];
            $reserva->documento_cliente = $data['documento_cliente'];
            $reserva->valor_cargo = $data['valor_cargo'];
            $reserva->canal = $data['canal'];
            $reserva->identificador_proceso = UuidHelper::uuid();
            $reserva->fecha_solicitud = date('Y-m-d H:i:s');
            if ($reserva->validate() && $reserva->save()) {
                return ['identificador_proceso' => $reserva->identificador_proceso];
            }
        }
        return ['message' => 'Error al tratar de guardar los datos'];
    }

}

