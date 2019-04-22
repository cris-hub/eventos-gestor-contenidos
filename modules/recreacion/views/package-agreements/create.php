<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\recreacion\models\Room */

$this->title = Yii::t('app', 'Create Package');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Packages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="room-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
