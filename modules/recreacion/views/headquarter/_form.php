<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use nemmo\attachments\components\AttachmentsInput;
<<<<<<< HEAD
use dosamigos\ckeditor\CKEditor;
=======

>>>>>>> 5ba415694db797831d7c1c031948a084aea5606a
/* @var $this yii\web\View */
/* @var $model app\modules\recreacion\models\Headquarter */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="headquarter-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'headquarterCode')->textInput(['maxlength' => true]) ?>

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

    <?= $form->field($model, 'emails')->textarea(['rows' => 6]) ?>

    <?php
    echo $form->field($model, 'status')->dropDownList(app\components\Util::getlistStatus(), ['prompt' => "Seleccione..."]);
    ?>

  <?php
    $citys = \app\modules\recreacion\models\City::find()->orderby('name')->all();
    $arrayCitys= ArrayHelper::map($citys, 'id', 'name');
    echo $form->field($model, 'cityId')->dropDownList($arrayCitys, ['prompt' => "Seleccione..."]);
    ?>


    <?=
    AttachmentsInput::widget([
        'id' => 'file-input',
        'model' => $model,
        'options' => [
            'multiple' => false,
        ],
        'pluginOptions' => [
            'maxFileCount' => 5,
            'showUpload' => false,
            'showUploadedThumbs' => false,
            'overwriteInitial' => true,
            'allowedFileExtensions' => ['jpg', 'png', 'jpeg'],
            'fileActionSettings' => [
                'showUpload' => false,
            ],
        ]
    ])
    ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
