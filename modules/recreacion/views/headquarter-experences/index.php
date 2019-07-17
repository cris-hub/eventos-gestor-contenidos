<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\recreacion\models\HeadquarterExperencesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Asignacion Sedes');
$this->params['breadcrumbs'][] = $this->title;

$eventTyoes = \app\modules\recreacion\models\Experences::find()->orderby('name')->all();
$arrayEventTyps = \yii\helpers\ArrayHelper::map($eventTyoes, 'id', 'name');

$Headquarters = \app\modules\recreacion\models\Headquarter::find()->orderby('name')->all();
$arrayHeadquarters = \yii\helpers\ArrayHelper::map($Headquarters, 'id', 'name');
?>
<div class="headquarter-experences-index">

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p>
        <?= Html::a(Yii::t('app', 'Asignar sedes a las experiencias'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'experencesId',
                'filter' => $arrayEventTyps,
                'value' => function($data) {
                    return $data->experences->name;
                }
            ],
            [
                'attribute' => 'headquarterId',
                'filter' => $arrayHeadquarters,
                'value' => function($data) {
                    return $data->headquarter->name;
                }
            ],
            [
                'attribute' => 'status',
                'filter' => app\components\Util::getlistStatus(),
                'value' => function($data) {
                    return app\components\Util::getStatus($data->status);
                }
            ],
            'created',
            'created_by',
            'modified_by',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>
    <?php Pjax::end(); ?>
</div>
