<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \mdm\admin\models\form\Signup */

$this->title = Yii::t('app', 'Create User');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup box box-primary">.
    <div class="box-body">
        <?= Html::errorSummary($model)?>
        <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
            <?= $form->field($model, 'username') ?>
            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'retypePassword')->passwordInput() ?>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                <?= Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-default']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
