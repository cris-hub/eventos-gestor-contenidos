<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\Util;
use app\modules\recreacion\models\Hotel;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\recreacion\models\RoomSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Rooms');
$this->params['breadcrumbs'][] = $this->title;

$hotels = Hotel::find()->orderby('name')->all();
$arrayHotels = ArrayHelper::map($hotels, 'id', 'name');
?>
<div class="room-index box box-primary">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="box-header with-border">
        <?= Html::a(Yii::t('app', 'Create Room'), ['create'], ['class' => 'btn btn-success']) ?>
    </div>
    <div class="box-body">

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
                'id',
                'type_package',
                'type_room',
                'name',
                [
                    'attribute' => 'description',
                    'format' => 'html',
                    'value' => function($data) {
                        if (!empty($data->description)) {
                            return substr($data->description, 0, 250) . '...';
                        } else {
                            return substr($data->description, 0, 150);
                        }
                    }
                ],
                'capacity_people',
                [
                    'attribute' => 'aditional_information',
                    'format' => 'html',
                    'value' => function($data) {
                        if (!empty($data->aditional_information)) {
                            return substr($data->aditional_information, 0, 250) . '...';
                        } else {
                            return substr($data->aditional_information, 0, 150);
                        }
                    }
                ],
                [
                    'attribute' => 'status',
                    'filter' => Util::getlistStatus(),
                    'value' => function($data) {
                        return Util::getStatus($data->status);
                    }
                ],
                [
                    'attribute' => 'hotel_id',
                    'filter' => $arrayHotels,
                    'value' => function($data) {
                        return $data->hotel->name;
                    }
                ],
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]);
        ?>
    </div>
</div>
