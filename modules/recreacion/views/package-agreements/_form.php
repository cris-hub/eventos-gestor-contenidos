<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\Util;
use app\modules\recreacion\models\HotelAgreements;
use dosamigos\ckeditor\CKEditor;
use yii\helpers\ArrayHelper;
use nemmo\attachments\components\AttachmentsInput;

/* @var $this yii\web\View */
/* @var $model app\modules\recreacion\models\Room */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="room-form box box-primary">
    <div class="box-body">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
        
    <?= $form->field($model, 'type_package')->textInput(['maxlength' => true]) ?>
        
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <!-- <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?> -->

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

    <?= $form->field($model, 'capacity_people')->textInput() ?>
    
    <?= $form->field($model, 'aditional_information')->widget(CKEditor::className(), [
            'options' => ['rows' => 6],
            'preset' => 'custom',
            'clientOptions' => [
                'language' => 'es',
                'extraPlugins' => 'justify',
                'toolbar' => 'full',
            ]
        ]) 
    ?>

    <?= $form->field($model, 'status')->dropDownList(Util::getlistStatus()) ?>

        
    <?php
        $hotels = HotelAgreements::find()->orderby('name')->all();
        $arrayHotels = ArrayHelper::map($hotels, 'id', 'name');            
        echo $form->field($model, 'hotel_id')->dropDownList($arrayHotels
                , ['prompt' => "Seleccione..."]);
    ?>
        
    <?= AttachmentsInput::widget([
            'id' => 'file-input',
            'model' => $model,
            'options' => [
                    'multiple' => true, 
            ],
            'pluginOptions' => [
                    'maxFileCount' => 10, 
                    'showUpload' => false, 
                    'showUploadedThumbs' => false, 
                    'overwriteInitial' => false, 
                    'allowedFileExtensions' => ['jpg', 'png', 'jpeg'],
                    'fileActionSettings' => [
                        'showUpload' => false, 
                    ],
            ]
        ]) 
    ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    </div>
</div>
