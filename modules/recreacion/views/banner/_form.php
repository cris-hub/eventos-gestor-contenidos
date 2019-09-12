<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use nemmo\attachments\components\AttachmentsInput;

/* @var $this yii\web\View */
/* @var $model app\modules\recreacion\models\Banner */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="banner-form box box-primary">
    <div class="box-body">
        <?php $form = ActiveForm::begin(['id' => 'banner-form', 'action' => ['banner/update', 'id' => 1]]); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        <?=
        AttachmentsInput::widget([
            'id' => 'file-input',
            'model' => $model,
            'options' => [
                'multiple' => false,
            ],
            'pluginOptions' => [
                'maxFileCount' => 1,
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
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
