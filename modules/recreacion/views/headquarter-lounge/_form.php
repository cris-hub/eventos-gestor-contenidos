<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\recreacion\models\Lounge;
use app\modules\recreacion\models\Headquarter;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\modules\recreacion\models\HeadquarterLounge */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="headquarter-lounge-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    $lounges = Lounge::find()->orderby('name')->all();
    $arraylounges = ArrayHelper::map($lounges, 'id', 'name');
    echo $form->field($model, 'loungeId')->dropDownList($arraylounges, ['prompt' => "Seleccione..."]);
    ?>

    <?php
    $Headquarters = Headquarter::find()->orderby('name')->all();
    $arrayheadquarters = ArrayHelper::map($Headquarters, 'id', 'name');
    echo $form->field($model, 'headquarterId')->dropDownList($arrayheadquarters, ['prompt' => "Seleccione..."]);
    ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
