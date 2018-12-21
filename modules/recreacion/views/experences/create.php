<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\recreacion\models\Experences */

$this->title = Yii::t('app', 'Create Experences');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Experences'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="experences-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
