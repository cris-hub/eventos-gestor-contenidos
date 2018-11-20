<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\recreacion\models\EventType */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Event Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-type-view">


    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ])
        ?>
        <?= Html::a(Yii::t('app', 'Cancel'), ['index', 'id' => $model->id], ['class' => 'btn btn-default']) ?>


    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'eventTypeCode',
            'name',
            'description:ntext',
            'status',
            'created',
            'created_by',
            'modified_by',
        ],
    ])
    ?>

</div>
