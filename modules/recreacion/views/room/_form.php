<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\Util;
use app\modules\recreacion\models\Hotel;
use app\modules\recreacion\models\Package;
use dosamigos\ckeditor\CKEditor;
use yii\helpers\ArrayHelper;
use nemmo\attachments\components\AttachmentsInput;
use kartik\select2\Select2;
use yii\widgets\Pjax;


?>

<div class="room-form box box-primary">
    <div class="box-body">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>


        <?php echo '<label class="control-label">Pauetes</label>';

        $packages = Package::find()
        ->where("hotel_id =:hotelId "
                . "AND status=:status", ['hotelId' => $model->hotel_id, 'status' => 'active'])
                ->all();
        $arrayPackages = ArrayHelper::map($packages, 'type_package','type_package',['name']);           
        


        
        echo $form->field($model, 'type_packages')->widget(Select2::classname(), [
        'data' => $arrayPackages,      
        'value' => $model->type_packages,      
        'options' => [
        'placeholder' => 'Select a package ...',
        'multiple' => true],
        'pluginOptions' => [
        'allowClear' => true,
        'tags' => true,
        'tokenSeparators' => [',', ' '],
        ],
        ]);
        ?>

    
    
    
    <?= $form->field($model, 'type_room')->textInput(['maxlength' => true]) ?>
        
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

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
        $hotels = Hotel::find()->orderby('name')->all();
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
