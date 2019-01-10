<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\recreacion\models\EventType;
use nemmo\attachments\components\AttachmentsInput;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\modules\recreacion\models\Lounge */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lounge-form">

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'loungeCode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'capacity')->textInput(['maxlength' => true]) ?>

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

    <?php
    echo $form->field($model, 'status')->dropDownList(app\components\Util::getlistStatus(), ['prompt' => "Seleccione..."]);
    ?>

    <?php
    $headquarters = \app\modules\recreacion\models\Headquarter::find()->orderby('name')->all();
    $arrayHeadquarters = ArrayHelper::map($headquarters, 'id', 'name');
    echo $form->field($model, 'headquarterId')->dropDownList($arrayHeadquarters, ['prompt' => "Seleccione..."]);
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
