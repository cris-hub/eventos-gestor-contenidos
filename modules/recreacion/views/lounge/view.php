<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\recreacion\models\Lounge */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Lounges'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lounge-view">


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

    <?php
    $Headquarters = \app\modules\recreacion\models\Headquarter::find()->orderby('name')->all();
    $arrayHeadquarters = \yii\helpers\ArrayHelper::map($Headquarters, 'id', 'name');
    ?>



    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'loungeCode',
            'name',
            'description:ntext',
            'capacity',
            [
                'attribute' => 'headquarterId',
                'filter' => $arrayHeadquarters,
                'value' => function($data) {
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

<div class="box box-primary">
    <div class="box-header with-border">
        <p><?= Yii::t('app', 'Images') ?></p>
    </div>
    <div class="box-body">
        <?= \nemmo\attachments\components\AttachmentsTable::widget(['model' => $model]) ?>
    </div>
</div>
