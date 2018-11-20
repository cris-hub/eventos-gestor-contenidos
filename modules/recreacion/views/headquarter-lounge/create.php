<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\recreacion\models\HeadquarterLounge */

$this->title = Yii::t('app', 'Create Headquarter Lounge');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Headquarter Lounges'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="headquarter-lounge-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
