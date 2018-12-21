<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\recreacion\models\EventTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tipos Eventos');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-type-index">
    <?php Pjax::begin(); ?>
<?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p>
<?= Html::a(Yii::t('app', 'Nuevo tipo evento'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'eventTypeCode',
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
            'modified',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>
<?php Pjax::end(); ?>
</div>
