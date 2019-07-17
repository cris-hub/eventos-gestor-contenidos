<?php

namespace app\modules\recreacion\controllers;

use app\modules\recreacion\controllers\RestconfigController;
use app\modules\recreacion\models\HotelAgreements;
use Exception;
use Yii;
use yii\web\HttpException;
use yii\helpers\HtmlPurifier;
use yii\rest\ActiveController;
use yii\web\Response;
use function GuzzleHttp\json_decode;

class ResthotelagreementsController extends ActiveController {

    public $modelClass = 'app\models\User';

    const MODULE = 'Recreacion';
    const ACTIVE = 'active';

    public function init() {
        parent::init();
        \Yii::$app->response->format = Response::FORMAT_JSON;
        \Yii::$app->user->enableSession = false;
    }

    public function behaviors() {
        $behaviors = RestauthController::behaviors();
        return $behaviors;
    }

    private function mapWithHotels($hotels) {
        foreach ($hotels as $hotel) {
            $response [] = self::mapWithHotel($hotel);
        }
        return $response;
    }

    private function mapWithHotel($hotel) {
        try {
            $images = self::getFilesFromModelFiles($hotel->files);
            return $model = [
                'id' => $hotel->id,
                'name' => $hotel->name . ' - ' . 'Convenio' ,
                'ishotelwithAgrements' => true,
                'max_guests' => $hotel->max_guests,
                'description' => $hotel->description,
                'slug' => $hotel->slug,
                'cell_phone' => $hotel->cell_phone,
                'address' => $hotel->address,
                'ubicacion' => $hotel->ubicacion,
                'hotel_code' => $hotel->hotel_code,
                'images' => $images,
                'phone' => $hotel->phone];
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function getFilesFromModelFiles($files) {
        if (!empty($files)) {
            foreach ($files as $file) {
                $images[] = ['url' => RestconfigController::getUrlImage($file->path)];
            }
            return $images;
        }
        return [];
    }

    private function searchhotels($hotelId, $cityId) {
        if (!empty($hotelId)) {
            $hotels = HotelAgreements::find()->where('id=:hotelId and status=:status', [
                                ':hotelId' => $hotelId,
                                ':status' => self::ACTIVE
                            ])
                            ->orderby('name')->all();
        } else {
            $hotels = HotelAgreements::find()->where('city_id=:cityId and status=:status', [
                                ':cityId' => $cityId,
                                ':status' => self::ACTIVE
                            ])
                            ->orderby('name')->all();
        }
        return $hotels;
    }

    private function getAllHotelsActivesFromCity() {
        if (isset($_POST['cityId']) && !empty($_POST['cityId']) && is_numeric($_POST['cityId'])) {

            $cityId = HtmlPurifier::process($_POST['cityId']);

            return HotelAgreements::find()
                            ->where('city_id=:cityId and status=:status', [':cityId' => $cityId, ':status' => self::ACTIVE])
                            ->orderby('name')->all();
        }
        return [];
    }

    public function actionGetlisthotelsbycity() {
        try {
            $hotels = self::getAllHotelsActivesFromCity();
            $response = $this->mapWithHotels($hotels);
            return $response;
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

    public function actionGethotelbyid() {
        try {
            $response = ['error' => true, 'msg' => 'Parametros incorrectos'];
            if (isset($_POST['id']) && !empty($_POST['id']) && is_numeric($_POST['id'])) {
                $response = [];
                $id = HtmlPurifier::process($_POST['id']);
                $hotel = HotelAgreements::find()->where('id=:id and status=:status', [':id' => $id, ':status' => self::ACTIVE])->one();

                $response [] = self::mapWithHotel($hotel);
            }
            return $response;
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

    public function actionSearchhotels() {
        try {
            if (isset($_POST['json']) && !empty($_POST['json'])) {
                $post = json_decode($_POST['json']);
                $hotelId = HtmlPurifier::process($post->hotelId);
                $cityId = HtmlPurifier::process($post->cityId);
                $hotels = self::searchhotels($hotelId, $cityId);
                $response = $this->mapWithHotels($hotels);
            }

            return $response;
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}
