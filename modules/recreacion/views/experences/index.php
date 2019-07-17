<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\recreacion\models\ExperencesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Experiencias');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="experences-index">

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p>
        <?= Html::a(Yii::t('app', 'Nueva Experiencia'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?php
    $eventTyoes = \app\modules\recreacion\models\EventType::find()->orderby('name')->all();
    $arrayEventTyps = \yii\helpers\ArrayHelper::map($eventTyoes, 'id', 'name');
    ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'experencesCode',
            'name',
            'description:ntext',
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
//            'imagenId',
            [
                'attribute' => 'eventTypeId',
                'filter' => $arrayEventTyps,
                'value' => function($data) {
                    return $data->eventType->name;
                }
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>
    <?php Pjax::end(); ?>
</div>
