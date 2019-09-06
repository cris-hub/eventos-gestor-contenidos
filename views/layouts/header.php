<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

$minilogo = Html::img('web/images/minilogo.png',["width" => '20px']);
$logo = Html::img('web/images/logo.png',["width" => '130px']);
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">'.$minilogo
            .'</span><span class="logo-lg">' . $logo 
            . '</span>', null, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <!-- Tasks: style can be found in dropdown.less -->
                <li class="dropdown tasks-menu">
                    
                    <?= Html::a(
                        '<i class="fa fa-power-off"></i> Salir',
                        ['/site/logout'],
                        ['data-method' => 'post']
                    ) ?>
                </li>
            </ul>
        </div>
    </nav>
</header>
