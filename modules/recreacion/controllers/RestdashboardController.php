<?php

namespace app\modules\recreacion\controllers;

use app\modules\recreacion\models\Banner;
use yii\rest\ActiveController;
use yii\web\Response;

class RestdashboardController extends ActiveController {

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

    public function actionGetimagebanner() {
        try {
            $idBanner = 1;
            $banner = Banner::find()->where('id=:id', [':id' => $idBanner])->one();
            $image = [];
            if (!empty($banner)) {
                foreach ($banner->files as $file) {
                    $image[] = ['url' => \app\modules\recreacion\controllers\RestconfigController::getUrlImage($file->path)];
                }
            }
            return $image;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

}
