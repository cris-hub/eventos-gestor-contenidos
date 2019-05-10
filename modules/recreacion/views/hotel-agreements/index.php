<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\recreacion\models\City;
use yii\helpers\ArrayHelper;
use app\components\Util;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\recreacion\models\HotelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Hotels');
$this->params['breadcrumbs'][] = $this->title;

$cities = City::find()->orderby('name')->all();
$arrayCities = ArrayHelper::map($cities, 'id', 'name'); 
?>
<div class="hotel-index box box-primary">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="box-header with-border">
        <?= Html::a(Yii::t('app', 'Create Hotel'), ['create'], ['class' => 'btn btn-success']) ?>
    </div>
    <div class="box-body">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            'hotel_code',
            // 'hotel_chain_code',
            'name',
            'max_guests',
            /*[
                'attribute'=>'description',
                'format'=>'html',
                'value'=>function($data){
                    if(!empty($data->description)){
                        return substr($data->description, 0,250).'...';
                    }else{
                       return substr($data->description, 0,150); 
                    }
                    
                }
            ],*/
            // 'slug',
            // 'cell_phone',
            [
                'attribute'=>'status',
                'filter'=> Util::getlistStatus(),
                'value'=>function($data){
                    return Util::getStatus($data->status);
                    
                }
            ],
            [
                'attribute'=>'city_id',
                'filter'=>$arrayCities,
                'value'=>function($data){
                    return $data->city->name;
                    
                }
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
</div>
