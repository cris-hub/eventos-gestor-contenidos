<?php

namespace app\modules\recreacion\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\Response;
use app\modules\recreacion\models\Room;
use app\modules\recreacion\models\Package;
use yii\helpers\HtmlPurifier;
use app\modules\recreacion\controllers\RestconfigController;

class RestroomController extends ActiveController {

    public $modelClass = 'app\models\User';

    const MODULE = 'Recreacion';
    const ACTIVE = 'active';

    public function init() {
        parent::init();
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->user->enableSession = false;
    }

    public function behaviors() {
        $behaviors = RestauthController::behaviors();
        return $behaviors;
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

    private function getAllFromPackageModelWithConditionFilter($post, $v) {
        if (isset($post->cityId) && !empty($post->cityId) && isset($post->checkIn) && !empty($post->checkIn) && isset($post->checkOut) && !empty($post->checkOut) && isset($post->guests) && !empty($post->guests) && isset($post->hotelId)) {
            $post = json_decode($_POST['json']);
            $hotelId = HtmlPurifier::process($post->hotelId);
            $guests = HtmlPurifier::process($post->guests);
            $packageName = HtmlPurifier::process($post->packageName);
            $guestsIn = self::allowedRange($guests);
            $package = Package::find()
                    ->where("type_package LIKE '%' :id '%'  "
                            . "AND status=:status "
                            . "AND name=:packageName "
                            , ['id' => $v,
                        'status' => self::ACTIVE,
                        'packageName' => $packageName])
                    ->andWhere(['in', 'capacity_people', $guestsIn])
                    ->andWhere(['and', 'hotel_id', $hotelId])
                    ->orderby('name')
                    ->all();

            return $package;
        }
    }

    private function mapWithRooms($rooms, $post) {
        foreach ($rooms as $room) {
            $model = self::mapWithRoom($room);
            if ($model['packagesLength'] > 0) {
                foreach ($model['packages'] as $v) {
                    $packages = self::getAllFromPackageModelWithConditionFilter($post, $v);
                    foreach ($packages as $key => $packa) {
                        $packageToSend = [
                            'roomPackage' => $packages[$key],
                            'images' => self ::getFilesFromModelFiles($packa->files),
                        ];
                        $model['package'] = $packageToSend;
                        $model['images'] = self::getFilesFromModelFiles($room->files);
                        $model['packagefiltered'] = $v;
                        $response [] = $model;
                    }
                }
            }
        }
        return $response;
    }

    private function mapWithRoom($room) {
        if (!empty($room)) {
            $images = self::getFilesFromModelFiles($room->files);
            $model = [
                'id' => $room->id,
                'name' => $room->name,
                'type_room' => $room->type_room,
                'description' => $room->description,
                'capacity_people' => $room->slug,
                'capacity_people' => $room->capacity_people,
                'aditional_information' => $room->aditional_information,
                'type_package' => $room->type_package,
                'slug' => $room->slug,
                'packages' => split(",", $room->type_package),
                'packagesLength' => sizeof(split(",", $room->type_package)),
                'images' => $images
            ];
            return $model;
        }
    }

    private function allowedRange($guests) {
        for ($i = $guests; $i < ($guests + 2); $i++) {
            $guestsIn[] = $i;
        }
        return $guestsIn;
    }

    private function getAllFromRoomModelWithConditionFilter($post) {
        try {
            if (isset($post->cityId) && !empty($post->cityId) && isset($post->checkIn) && !empty($post->checkIn) && isset($post->checkOut) && !empty($post->checkOut) && isset($post->guests) && !empty($post->guests) && isset($post->hotelId)) {
                $post = json_decode($_POST['json']);
                $hotelId = HtmlPurifier::process($post->hotelId);
                $guests = HtmlPurifier::process($post->guests);
                $guestsIn = self::allowedRange($guests);

                return Room::find()->where('hotel_id=:hotelId '
                                        . 'and status=:status', [
                                    'hotelId' => $hotelId,
                                    'status' => self::ACTIVE
                                ])
                                ->andWhere(['in', 'capacity_people', $guestsIn])
                                ->orderby('name')->all();
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function actionGetroombynamepackage() {
        try {
            if (isset($_POST['json']) && !empty($_POST['json'])) {
                $post = json_decode($_POST['json']);
                $rooms = self::getAllFromRoomModelWithConditionFilter($post);
                $response = self::mapWithRooms($rooms, $post);
            }
            return $response;
        } catch (Exception $exc) {
            \Yii::error(__FILE__ . ':' . __LINE__ . '{'
                    . print_r(' {ERROR [' . $exc->getMessage()
                            . '}', true) . '}', 'REST');
            return ['error' => true, 'info' => $exc->getMessage(), 'msg' => Yii::$app->params['errorRest']];
        }
    }

}
