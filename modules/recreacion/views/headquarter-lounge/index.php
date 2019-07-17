<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\recreacion\models\HeadquarterLoungeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Headquarter Lounges');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="headquarter-lounge-index">

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Headquarter Lounge'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'loungeId',
            'headquarterId',
            'created',
            'created_by',
            'modified_by',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['delete', 'loungeId' => $model['loungeId'], 'headquarterId' => $model['headquarterId']], [
                                    'title' => Yii::t('app', 'delete'),
                                    'method' => 'post',
                                    'data-pjax' => '0',
                        ]);
                    },
                ]
            ],
        ],
    ]);
    ?>
    <?php Pjax::end(); ?>
</div>
