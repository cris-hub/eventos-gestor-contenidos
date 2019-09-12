<?php 
$userName = isset(Yii::$app->user->identity->username) ? Yii::$app->user->identity->username : '';
?>

<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">                
                <?= \yii\helpers\Html::img('web/images/user2.png',
                        ["class" => 'img-circle'], ['alt' => 'User Image', 'width'=>'350px']) ?>
            </div>
            <div class="pull-left info">
                <p><?= $userName?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <hr>
        <!-- /.search form -->
        
        <?php
        if (!\Yii::$app->user->isGuest) {  
            $callback = function ($menu) {                    
                if (count($menu['children']) > 0) {
                    $item = [
                        'label' => Yii::t('app', $menu['name']),
                        'url' => [$menu['route']],
                        'icon' => $menu['data'],
                        'options' => [$menu['data'], 'class' => 'treeview'],
                        'items' => $menu['children']
                    ];
                } else {
                    $item = [
                        'label' => Yii::t('app', $menu['name']),
                        'icon' => $menu['data'],
                        'url' => [$menu['route']],
                    ];
                }                
                return $item;
            };

            $items = \mdm\admin\components\MenuHelper::getAssignedMenu(
                    Yii::$app->user->id, null,$callback);
          
            echo \dmstr\widgets\Menu::widget([
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => $items,
                //'submenuTemplate' => "<ul class='treeview-menu'>\n{items}\n</ul>\n",
                'submenuTemplate' => "<ul class='treeview-menu'>\n{items}\n</ul>\n",
                'encodeLabels' => false, //allows you to use html in labels
                'activateParents' => false,]);
        }
        ?>
    </section>
</aside>
