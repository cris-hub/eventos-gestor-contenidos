<?php

namespace app\modules\recreacion\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\Response;
use app\modules\recreacion\models\PackageAgreements;
use stdClass;
use yii\helpers\HtmlPurifier;
use app\modules\recreacion\controllers\RestconfigController;

class RestpackageagreementsController extends ActiveController {

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

    private function allowedRange($guests) {
        for ($i = $guests; $i < ($guests + 2); $i++) {
            $guestsIn[] = $i;
        }
        return $guestsIn;
    }

    private function mapWithPackages($packages) {
        if (!empty($packages)) {
            foreach ($packages as $key => $packa) {
                $response [] = self::mapWithPackage($packages[$key]);
            }
        }
        return $response;
    }

    private function mapWithPackage($packa) {
        $model = new stdClass();
        $images = self::getFilesFromModelFiles($packa->files);
        $packageToSend = [
            'roomPackage' => $packa,
            'images' => $images
        ];

        $model->images = $images;
        $model->package = $packageToSend;
        return clone ($model);
    }

    private function getAllPackageFromModel($post) {
        if (isset($post->cityId) && !empty($post->cityId) && isset($post->checkIn) && !empty($post->checkIn) && isset($post->checkOut) && !empty($post->checkOut) && isset($post->guests) && !empty($post->guests) && isset($post->hotelId)) {
            $hotelId = HtmlPurifier::process($post->hotelId);
            $guests = HtmlPurifier::process($post->guests);
            $guestsIn = self::allowedRange($guests);
            $packages = PackageAgreements::find()
                            ->where("hotel_id =:hotelId "
                                    . "AND status=:status", ['hotelId' => $hotelId, 'status' => self::ACTIVE])
                            // ->andWhere(['in', 'capacity_people', $guestsIn])
                            ->groupby('name')->orderby('name')->all();
            return $packages;
        }
    }

    public function actionGetpackages() {
        try {
            if (isset($_POST['json']) && !empty($_POST['json'])) {
                $post = json_decode($_POST['json']);
                $packages = self::getAllPackageFromModel($post);
                $response = self::mapWithPackages($packages);
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
