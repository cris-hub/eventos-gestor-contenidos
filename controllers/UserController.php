<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\PasswordResetRequest;
use mdm\admin\models\form\ResetPassword;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;

class UserController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'logout' => ['post'],
                    'activate' => ['post'],
                ],
            ],
        ];
    }

    
    /**
     * Signup new user
     * @return string
     */
    public function actionCreate()
    {
        $model = new \mdm\admin\models\form\Signup();
        if ($model->load(Yii::$app->getRequest()->post())) {
            if ($user = $model->signup()) {
                return $this->redirect(['admin/user/index']);
            }
        }

        return $this->render('signup', [
                'model' => $model,
        ]);
    }

    /**
     * Request reset password
     * @return string
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequest();
        if ($model->load(Yii::$app->getRequest()->post()) && $model->validate()) {

            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Revise su correo electrónico para obtener más instrucciones.');

                return $this->redirect(['/admin/user/login']);
            } else {
                Yii::$app->getSession()->setFlash('error', 'Lo sentimos, no podemos restablecer la contraseña para el correo electrónico proporcionado.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Reset password
     * @return string
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPassword($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->getRequest()->post()) && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'Se guardó una nueva contraseña.');

            return $this->redirect(['/admin/user/login']);
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

}
