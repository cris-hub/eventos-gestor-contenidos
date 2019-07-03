<?php

namespace app\modules\recreacion\controllers;
use Yii;
use yii\rest\ActiveController;

class RestconfigController extends ActiveController {

    public $modelClass = 'app\models\User';

    const MODULE = 'Recreacion';
    const ACTIVE = 'active';


    public static function getUrlImage($path) {
        try {
            $siteUrl = self::getSiteurl();
            $tempPath = str_replace(Yii::$app->basePath, Yii::$app->params['urlBlob']. '/..', $path);
            return str_replace('\\', '/', $tempPath);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public static function getSiteurl() {
        try {
            $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ||
                    $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $domainName = $_SERVER['HTTP_HOST'];
            return $protocol . $domainName;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

}
