<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\Util;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\recreacion\models\Experences */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Experences'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="experences-view">


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
            'experencesCode',
            'name',
            'description:ntext',
            'status',
            'created',
            'created_by',
            'modified_by',
            'imagenId',
            'eventTypeId',
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