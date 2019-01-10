<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/bootstrap-fileinput/fileinput.css',
        'css/bootstrap-fileinput/fileinput-rtl.css',
        //'datatables/dataTables.bootstrap.css',

    ];
    public $js = [
        'js/bootstrap-fileinput/fileinput.min.js',
        'js/bootstrap-fileinput/plugins/piexif.min.js',
        'js/bootstrap-fileinput/plugins/purify.min.js',
        'js/bootstrap-fileinput/plugins/sortable.min.js',
        'js/bootstrap-fileinput/locales/es.js',
        //'datatables/dataTables.bootstrap.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'dmstr\web\AdminLteAsset',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}
