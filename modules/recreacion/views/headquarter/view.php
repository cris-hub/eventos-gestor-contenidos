<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\recreacion\models\Headquarter */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Headquarters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="headquarter-view">


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
            'headquarterCode',
            'name:html',
            'description:html',
            'emails',
            'status',
            'created',
            'created_by',
            'modified_by',
            [
                'attribute' => 'cityId',
                'filter' => app\modules\recreacion\models\City::find()->all(),
                'value' => function($data) {
                    return $data->city->name;
                }
            ],
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