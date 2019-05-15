<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\Util;

/* @var $this yii\web\View */
/* @var $model app\modules\recreacion\models\Room */

$this->title = Yii::t('app', 'PackageAgreements');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'PackageAgreements'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="room-view box box-primary">

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
            'type_package',
            'name',
            'slug',
            [
                'attribute'=>'description',
                'format'=>'html',
            ],
            'capacity_people',
            [
                'attribute'=>'aditional_information',
                'format'=>'html',
            ],
            [
                'attribute'=>'status',
                'value'=> Util::getStatus($model->status),
            ],
            'created',
            'created_by',
            'modified',
            'modified_by',
            [
                'attribute'=>'hotel_id',
                'format'=>'html',
                'value'=> Html::a($model->hotel->name,
                            ['hotel/view', 'id'=>$model->hotel_id]),
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
