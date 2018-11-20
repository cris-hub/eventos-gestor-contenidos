<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $user mdm\admin\models\User */

$resetLink = Url::to(['user/reset-password','token'=>$user->password_reset_token], true);
$logo = Html::img('logo_email.png', ['width' => '230px']);
?>
<div class="password-reset">
    <p>Hola <?= Html::encode($user->username) ?>,</p>

    <p>Siga el siguiente enlace para restablecer su contraseña:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
<p><small><em>La información contenida en este correo electrónico, así como en cualquiera de
        sus archivos adjuntos, es confidencial y reservada, y está dirigida
        exclusivamente a él o los destinatarios indicados. Por lo tanto, su divulgación,
        reproducción o distribución está estrictamente prohibida, así como cualquier uso
        para algún fin diferente al indicado en el presente correo</em></small></p>
<div class="login-logo">
    <?= $logo ?>
</div>
