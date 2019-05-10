<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\Util;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\recreacion\models\Hotel */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hotels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="hotel-view box box-primary">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <div class="box-header with-border">
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a(Yii::t('app', 'Cancel'), ['index', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </div>
    <div class="box-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'max_guests',
            'hotel_code',
            // 'hotel_chain_code',
            [
                'attribute'=>'description',
                'format'=>'html',
            ],
            'slug',
            'cell_phone',
            'address',  [
                'attribute'=>'ubicacion',
                'format'=>'html',
            ],
            'phone',
            [
                'attribute'=>'status',
                'value'=> Util::getStatus($model->status),
            ],
            'created',
            'created_by',
            'modified',
            'modified_by',
            [
                'attribute'=>'city_id',
                'value'=>$model->city->name,
            ],
        ],
    ]) ?>
    </div>
</div>
<div class="box box-primary">
    <div class="box-header with-border">
        <p><?= Yii::t('app', 'Images') ?></p>
    </div>
    <div class="box-body">
        <?= \nemmo\attachments\components\AttachmentsTable::widget(['model' => $model]) ?>
    </div>
</div>
<div class="box box-primary">
    <div class="box-header with-border">
        <p><?= Yii::t('app', 'Rooms') ?></p>
    </div>
    <div class="box-body">
        <?= GridView::widget([
            'dataProvider' => new ArrayDataProvider(['allModels' => $model->roomAgreements]),
            'layout' => '{items}',
            'columns' => [
                'id',
                'type_room',
                'name',
                [
                    'attribute'=>'description',
                    'format'=>'html',
                    'value'=>function($data){
                        if(!empty($data->description)){
                            return substr($data->description, 0,250).'...';
                        }else{
                           return substr($data->description, 0,150); 
                        }

                    }
                ],  
                'capacity_people',
                [
                    'attribute'=>'aditional_information',
                    'format'=>'html',
                    'value'=>function($data){
                        if(!empty($data->aditional_information)){
                            return substr($data->aditional_information, 0,250).'...';
                        }else{
                           return substr($data->aditional_information, 0,150); 
                        }

                    }
                ],
                [
                    'attribute'=>'status',
                    'filter'=> Util::getlistStatus(),
                    'value'=>function($data){
                        return Util::getStatus($data->status);

                    }
                ],
                ['class' => 'yii\grid\ActionColumn',
                'template' => '{view}{update}{delete}',
                    'buttons' => [
                        'view' => function ($url, $data) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-eye-open"></span>',
                                    ['room/view', 'id'=>$data->id]);
                        },                                           
                        'update' => function ($url, $data) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-pencil"></span>',
                                    ['room/update', 'id'=>$data->id]);
                        },                                           
                        'delete' => function ($url, $data) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-trash"></span>',
                                    '#',
                                    [
                                        'class' => 'delete-button',
                                        'title' => Yii::t('yii', 'Delete'),
                                        'data-url' => Url::to(['room/delete', 
                                            'id' => $data->id,
                                            'hotelId' => $data->hotel_id])
                                    ]
                                );
                        }                                           
                    ]
                ],
            ],
        ]); ?>
    </div>
</div>
