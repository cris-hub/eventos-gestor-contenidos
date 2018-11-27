<?php

namespace app\modules\recreacion\controllers;

use Yii;
use app\modules\recreacion\models\HeadquarterLounge;
use app\modules\recreacion\models\HeadquarterLoungeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * HeadquarterLoungeController implements the CRUD actions for HeadquarterLounge model.
 */
class HeadquarterLoungeController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
        ];
    }

    /**
     * Lists all HeadquarterLounge models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HeadquarterLoungeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single HeadquarterLounge model.
     * @param integer $loungeId
     * @param integer $headquarterId
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($loungeId, $headquarterId)
    {
        return $this->render('view', [
            'model' => $this->findModel($loungeId, $headquarterId),
        ]);
    }

    /**
     * Creates a new HeadquarterLounge model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new HeadquarterLounge();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'loungeId' => $model->loungeId, 'headquarterId' => $model->headquarterId]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing HeadquarterLounge model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $loungeId
     * @param integer $headquarterId
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($loungeId, $headquarterId)
    {
        $model = $this->findModel($loungeId, $headquarterId);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'loungeId' => $model->loungeId, 'headquarterId' => $model->headquarterId]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing HeadquarterLounge model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $loungeId
     * @param integer $headquarterId
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($loungeId, $headquarterId)
    {
        $this->findModel($loungeId, $headquarterId)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the HeadquarterLounge model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $loungeId
     * @param integer $headquarterId
     * @return HeadquarterLounge the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($loungeId, $headquarterId)
    {
        if (($model = HeadquarterLounge::findOne(['loungeId' => $loungeId, 'headquarterId' => $headquarterId])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
