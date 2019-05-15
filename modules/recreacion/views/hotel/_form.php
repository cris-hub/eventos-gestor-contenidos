<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use dosamigos\ckeditor\CKEditor;
use yii\helpers\ArrayHelper;
use nemmo\attachments\components\AttachmentsInput;
use app\modules\recreacion\models\City;
use app\components\Util;


/* @var $this yii\web\View */
/* @var $model app\modules\recreacion\models\Hotel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hotel-form box box-primary">
    <div class="box-body">
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <?= $form->field($model, 'hotel_chain_code')->textInput(['maxlength' => true]) ?>
        
        <?= $form->field($model, 'hotel_code')->textInput(['maxlength' => true]) ?>
        
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        
        <?= $form->field($model, 'max_guests')->textInput(['maxlength' => true]) ?>

        <?=
        $form->field($model, 'description')->widget(CKEditor::className(), [
            'options' => ['rows' => 6],
            'preset' => 'custom',
            'clientOptions' => [
                'language' => 'es',
                'extraPlugins' => 'justify',
                'toolbar' => 'full',
            ]
        ])
        ?>
  

        <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'cell_phone')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'address')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'status')->dropDownList(Util::getlistStatus()) ?>

        <?php
        $cities = City::find()->orderby('name')->all();
        $arrayCities = ArrayHelper::map($cities, 'id', 'name');
        echo $form->field($model, 'city_id')->dropDownList($arrayCities
                , ['prompt' => "Seleccione..."]);
        ?>

        <?=
        AttachmentsInput::widget([
            'id' => 'file-input',
            'model' => $model,
            'options' => [
                'multiple' => true,
            ],
            'pluginOptions' => [
                'maxFileCount' => 5,
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
        <?= $form->field($model, 'ubicacion')->textarea(['rows' => 12]) ?>

        <div  style="justify-content: center;
        display: flex;" class="box-body">
        <?php echo  htmlspecialchars_decode($model->ubicacion); ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            <?= Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
