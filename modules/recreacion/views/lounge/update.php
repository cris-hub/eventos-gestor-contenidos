<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\recreacion\models\Lounge */

$this->title = Yii::t('app', 'Modificar salon: ' . $model->name, [
    'nameAttribute' => '' . $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Lounges'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="lounge-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
