<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\recreacion\models\Eventos */

$this->title = Yii::t('app', 'Update Eventos: ' . $model->even_id, [
    'nameAttribute' => '' . $model->even_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Eventos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->even_id, 'url' => ['view', 'id' => $model->even_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="eventos-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
