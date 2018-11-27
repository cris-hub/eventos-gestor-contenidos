<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\recreacion\models\HeadquarterExperences */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="headquarter-experences-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    $eventTyoes = app\modules\recreacion\models\Experences::find()->orderby('name')->all();
    $arrayexperences = \yii\helpers\ArrayHelper::map($eventTyoes, 'id', 'name');
    echo $form->field($model, 'experencesId')->dropDownList($arrayexperences, ['prompt' => "Seleccione..."]);
    ?>

    <?php
    $Headquarters = \app\modules\recreacion\models\Headquarter::find()->orderby('name')->all();
    $arrayheadquarters = \yii\helpers\ArrayHelper::map($Headquarters, 'id', 'name');
    echo $form->field($model, 'headquarterId')->dropDownList($arrayheadquarters, ['prompt' => "Seleccione..."]);
    ?>

    <?php

    echo $form->field($model, 'status')->dropDownList(app\components\Util::getlistStatus(), ['prompt' => "Seleccione..."]);
    ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
