<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\recreacion\models\HeadquarterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Sedes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="headquarter-index">

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p>
        <?= Html::a(Yii::t('app', 'Nueva sede'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
    $citys = \app\modules\recreacion\models\City::find()->orderby('name')->all();
    $arrayCitys = \yii\helpers\ArrayHelper::map($citys, 'id', 'name');
    ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'headquarterCode',
<<<<<<< HEAD
            'name:html',
            'description:html',
=======
            'name',
            'description:ntext',
>>>>>>> 5ba415694db797831d7c1c031948a084aea5606a
            'emails',
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
            [
                'attribute' => 'cityId',
                'filter' => $arrayCitys,
                'value' => function($data) {
                    return $data->city->name;
                }
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>
    <?php Pjax::end(); ?>
</div>
