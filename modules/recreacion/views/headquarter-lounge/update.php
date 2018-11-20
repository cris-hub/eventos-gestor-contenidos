<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\recreacion\models\HeadquarterLounge */

$this->title = Yii::t('app', 'Update Headquarter Lounge: ' . $model->loungeId, [
    'nameAttribute' => '' . $model->loungeId,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Headquarter Lounges'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->loungeId, 'url' => ['view', 'loungeId' => $model->loungeId, 'headquarterId' => $model->headquarterId]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="headquarter-lounge-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
