<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\recreacion\models\HeadquarterLounge */

$this->title = $model->loungeId;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Headquarter Lounges'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="headquarter-lounge-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'loungeId' => $model->loungeId, 'headquarterId' => $model->headquarterId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'loungeId' => $model->loungeId, 'headquarterId' => $model->headquarterId], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'created',
            'created_by',
            'modified_by',
            'loungeId',
            'headquarterId',
        ],
    ]) ?>

</div>
