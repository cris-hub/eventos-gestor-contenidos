<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\recreacion\models\AttachFile */

$this->title = Yii::t('app', 'Create Attach File');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Attach Files'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attach-file-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
