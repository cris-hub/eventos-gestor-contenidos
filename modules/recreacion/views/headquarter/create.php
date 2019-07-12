<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\recreacion\models\Headquarter */

$this->title = Yii::t('app', 'Nueva sede');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Headquarters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="headquarter-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
