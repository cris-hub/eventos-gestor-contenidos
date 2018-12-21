<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\recreacion\models\HeadquarterExperences */

$this->title = Yii::t('app', 'Modificar asignacion ' , [
    'nameAttribute' => '' . $model->experencesId,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Headquarter Experences'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->experencesId, 'url' => ['view', 'experencesId' => $model->experencesId, 'headquarterId' => $model->headquarterId]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="headquarter-experences-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
