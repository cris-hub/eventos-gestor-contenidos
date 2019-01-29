<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
<<<<<<< HEAD
use dosamigos\ckeditor\CKEditor;
=======

>>>>>>> 5ba415694db797831d7c1c031948a084aea5606a
/* @var $this yii\web\View */
/* @var $model app\modules\recreacion\models\EventType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="event-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'eventTypeCode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<<<<<<< HEAD
    <?= $form->field($model, 'description')->widget(CKEditor::className(), [
            'options' => ['rows' => 6],
            'preset' => 'custom',
            'clientOptions' => [
                'language' => 'es',
                'extraPlugins' => 'justify',
                'toolbar' => 'full',
            ]
        ])
         ?>
=======
    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
>>>>>>> 5ba415694db797831d7c1c031948a084aea5606a

    <?php
    echo $form->field($model, 'status')->dropDownList(app\components\Util::getlistStatus(), ['prompt' => "Seleccione..."]);
    ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
