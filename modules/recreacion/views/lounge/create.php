<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\recreacion\models\Lounge */

$this->title = Yii::t('app', 'Agregar salon');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Lounges'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lounge-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
