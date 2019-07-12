<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\recreacion\models\Authapirest */

$this->title = Yii::t('app', 'Create Authapirest');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Authapirests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authapirest-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
