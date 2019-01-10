<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \mdm\admin\models\form\PasswordResetRequest */

$this->title = 'Recuperación de contraseña';
$this->params['breadcrumbs'][] = $this->title;
$logo = Html::img('images/logo.png',["width" => '230px']);
?>
<div class="login-box">
    <div class="login-logo">
        <?= $logo ?>
    </div>

    <div class="login-box-body">
        <p class="login-box-msg"><?= Yii::t('app', $this->title) ?></p>
        <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'email')->textInput(['placeholder' => 'Correo electrónico']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-5">
                <div style="color:#999;margin:1em 0">
                    <?= Html::a(Yii::t('app', 'Sign in'), ['admin/user/login']) ?>.
                </div>
            </div>
            <div class="col-xs-7 text-right">
                <?= Html::submitButton(Yii::t('app', 'reset it'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

</div>
