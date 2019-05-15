<?php

namespace app\modules\recreacion\controllers;

use app\modules\recreacion\models\City;
use Yii;
use yii\db\Exception;
use yii\rest\ActiveController;
use yii\web\Response;

class RestcityController extends ActiveController {

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

    public function actionGetallcities() {
        try {
            return City::find()
                            ->join(
                                    'LEFT JOIN', 'hotel', 'hotel.city_id = city.id'
                            )
                            ->join(
                                    'LEFT JOIN', 'hotel_agreements', 'hotel_agreements.city_id = city.id'
                            )
                            ->groupBy('city.id')
                            ->orderby('name')->all();
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}
