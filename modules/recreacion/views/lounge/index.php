<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\recreacion\models\LoungeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Salones');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lounge-index">

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p>
        <?= Html::a(Yii::t('app', 'Agregar salon'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
    $Headquarters = \app\modules\recreacion\models\Headquarter::find()->orderby('name')->all();
    $arrayHeadquarters = \yii\helpers\ArrayHelper::map($Headquarters, 'id', 'name');
    ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'loungeCode',
            'name',
            'description:ntext',
            'capacity',
            [
                'attribute' => 'status',
                'filter' => app\components\Util::getlistStatus(),
                'value' => function($data) {
                    return app\components\Util::getStatus($data->status);
                }
            ],
            [
                'attribute' => 'headquarterId',
                'filter' => $arrayHeadquarters,
                'value' => function($data) {
                    return $data->headquarter->name;
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
