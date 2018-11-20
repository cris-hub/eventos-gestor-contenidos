<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \mdm\admin\models\form\ResetPassword */

$this->title = 'Cambiar contraseña';
$this->params['breadcrumbs'][] = $this->title;
$logo = Html::img('images/logo.png',["width" => '230px']);
?>
<div class="login-box">
    <div class="login-logo">
        <?= $logo ?>
    </div>
    <div class="login-box-body">
        <p class="login-box-msg"><?= Yii::t('app', $this->title) ?></p>
        <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
        <div class="row">
            <div class="col-xs-12">
                    <?= $form->field($model, 'password')->passwordInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 text-right">
                <?= Html::submitButton(Yii::t('app', 'Change Password'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

</div>
