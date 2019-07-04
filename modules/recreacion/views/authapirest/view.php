<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\recreacion\models\Authapirest */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Authapirests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authapirest-view box box-primary">

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
            'username',
            'password',
            'module',
            'created',
            'created_by',
            'modified',
            'modified_by',
        ],
    ]) ?>

    </div>
</div>
