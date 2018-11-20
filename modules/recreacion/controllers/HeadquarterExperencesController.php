<?php

namespace app\modules\recreacion\controllers;

use Yii;
use app\modules\recreacion\models\HeadquarterExperences;
use app\modules\recreacion\models\Experences;
use app\modules\recreacion\models\Headquarter;
use app\modules\recreacion\models\HeadquarterExperencesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * HeadquarterExperencesController implements the CRUD actions for HeadquarterExperences model.
 */
class HeadquarterExperencesController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all HeadquarterExperences models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new HeadquarterExperencesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);



        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single HeadquarterExperences model.
     * @param integer $experencesId
     * @param integer $headquarterId
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($experencesId, $headquarterId) {
        return $this->render('view', [
                    'model' => $this->findModel($experencesId, $headquarterId),
        ]);
    }

    /**
     * Creates a new HeadquarterExperences model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new HeadquarterExperences();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'experencesId' => $model->experencesId, 'headquarterId' => $model->headquarterId]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing HeadquarterExperences model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $experencesId
     * @param integer $headquarterId
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($experencesId, $headquarterId) {
        $model = $this->findModel($experencesId, $headquarterId);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'experencesId' => $model->experencesId, 'headquarterId' => $model->headquarterId]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing HeadquarterExperences model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $experencesId
     * @param integer $headquarterId
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($experencesId, $headquarterId) {

        $model = HeadquarterExperences::find()
                        ->where('experencesId=:experencesId and headquarterId=:headquarterId', [':experencesId' => $experencesId, ':headquarterId' => $headquarterId])->one();

//        $model->getExperences()->one();

        if ($model->status == 'inactive') {
            $model->status = 'active';
        } else {
            $model->status = 'inactive';
        }

        if ($model->save()) {
            return $this->redirect(['index']);
        }


        return $this->redirect(['index']);
    }

    /**
     * Finds the HeadquarterExperences model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $experencesId
     * @param integer $headquarterId
     * @return HeadquarterExperences the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($experencesId, $headquarterId) {
        if (($model = HeadquarterExperences::findOne(['experencesId' => $experencesId, 'headquarterId' => $headquarterId])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
