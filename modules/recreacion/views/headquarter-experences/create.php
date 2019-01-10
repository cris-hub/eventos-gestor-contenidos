<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\recreacion\models\HeadquarterExperences */

$this->title = Yii::t('app', 'Nueva experencia');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Headquarter Experences'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="headquarter-experences-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
