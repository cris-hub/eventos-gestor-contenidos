<?php

namespace app\modules\recreacion\controllers;

use yii\web\Controller;

/**
 * Default controller for the `recreacion` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
