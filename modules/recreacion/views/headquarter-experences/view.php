<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\recreacion\models\HeadquarterExperences */

$this->title = $model->experencesId;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Headquarter Experences'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="headquarter-experences-view">


    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'experencesId' => $model->experencesId, 'headquarterId' => $model->headquarterId], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a(Yii::t('app', 'Delete'), ['delete', 'experencesId' => $model->experencesId, 'headquarterId' => $model->headquarterId], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ])
        ?>
        <?= Html::a(Yii::t('app', 'Cancel'), ['index', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </p>


    <?php
    $eventTyoes = \app\modules\recreacion\models\Experences::find()->orderby('name')->all();
    $arrayEventTyps = \yii\helpers\ArrayHelper::map($eventTyoes, 'id', 'name');

    $Headquarters = \app\modules\recreacion\models\Headquarter::find()->orderby('name')->all();
    $arrayHeadquarters = \yii\helpers\ArrayHelper::map($Headquarters, 'id', 'name');
    ?>
    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'experencesId',
                'filter' => $arrayEventTyps,
                'value' => function($data) {
                    $this->title = 'Experiencia :'.$data->experences->name;
                    return $data->experences->name;
                }
            ],
            [
                'attribute' => 'headquarterId',
                'filter' => $arrayHeadquarters,
                'value' => function($data) {
                $this->title =  $this->title .'   -  Sede :'.$data->experences->name;
                    return $data->headquarter->name;
                }
            ],
            'status',
            'created',
            'created_by',
            'modified_by',
        ],
    ])
    ?>

</div>
