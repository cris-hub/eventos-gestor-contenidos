<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'name' => 'Recreacion',
    'timeZone' => 'America/Bogota',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'modules' => [
        'admin' => [
            'class' => 'mdm\admin\Module',
        //'layout' => 'left-menu',
        //'controllerMap' => [
        //    'assignment' => [
        //        'class' => 'mdm\admin\controllers\AssignmentController',
        //'userClassName' => 'app\models\Usuarios', // fully qualified class name of your User model
        // Usually you don't need to specify it explicitly, since the module will detect it automatically
        //'idField' => 'id',        // id field of your User model that corresponds to Yii::$app->user->id
        //'usernameField' => 'username', // username field of your User model
        //'searchClass' => 'app\models\UsuariosSearch'    // fully qualified class name of your User model for searching
        //    ]
        //],
        ],
        'recreacion' => [
            'class' => 'app\modules\recreacion\recreacion',
        ],
        'medicines' => [
            'class' => 'app\modules\medicines\Module',
        ],
        'attachments' => [
            'class' => nemmo\attachments\Module::className(),
            'tempPath' => '@app/uploads/temp',
            'storePath' => '@app/uploads/store',
            'rules' => [// Rules according to the FileValidator
                'maxFiles' => 10, // Allow to upload maximum 3 files, default to 3
                'mimeTypes' => ['image/png', 'image/jpeg'], // Only png images
                'maxSize' => 1024 * 1024 // 1 MB
            ],
            //'tableName' => '{{%attachments}}', // Optional, default to 'attach_file'
            'controllerMap' => [
                'migrate' => [
                    'class' => 'yii\console\controllers\MigrateController',
                    'migrationNamespaces' => [
                        'nemmo\attachments\migrations',
                    ],
                ],
            ],
        ],
//        'markdown' => [
//		'class' => 'kartik\markdown\Module',
//	]
    ],
    'components' => [
        'jwt' => [
            'class' => 'sizeg\jwt\Jwt',
            'key' => 'uy56yj89refw34rd',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'assetManager' => [
            'bundles' => [
                'dmstr\web\AdminLteAsset' => [
                    'skin' => 'skin-blue',
                ],
            ],
            'linkAssets' => true,
        ],
        'util' => [
            'class' => 'app\components\Util',
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@vendor/mdmsoft/yii2-admin/views' => '@app/views',
                #'@vendor/mdmsoft/yii2-admin/controllers' => '@app/controllers',
                #'@app/views' => '@vendor/mdmsoft/yii2-admin/views' 
                ],
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'IGRC4CUyEW1ej6RE-Q1buiKZyYA6psxa',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        //'user' => [
        //    'identityClass' => 'app\models\User',
        //    'enableAutoLogin' => true,
        //],
        'user' => [
            'identityClass' => 'app\models\User',
            //'identityClass' => 'mdm\admin\models\User',
            'loginUrl' => ['admin/user/login'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'jesusmatiz35@gmail.com',
                'password' => 'M1t3z2018*',
                'port' => '587',
                'encryption' => 'tls',
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'baseUrl' => '/index.php',
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' =>
                    [
                        '\app\modules\recreacion\controllers\restlounge',
                        '\app\modules\recreacion\controllers\restheadquarter',
                        '\app\modules\recreacion\controllers\restexperences',
                        '\app\modules\recreacion\controllers\resthotel',
                        '\app\modules\recreacion\controllers\resthotelagreements',
                        '\app\modules\recreacion\controllers\restcity',
                        '\app\modules\recreacion\controllers\restauth',
                        '\app\modules\recreacion\controllers\restdashboard',
                        '\app\modules\recreacion\controllers\restconfig',
                        '\app\modules\recreacion\controllers\restroom',
                        '\app\modules\recreacion\controllers\restroomagreements',
                        '\app\modules\recreacion\controllers\restpackage',
                        '\app\modules\recreacion\controllers\restpackageagreements',
                    ],

                ],

            ],
        ],
    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            'site/*',
            'user/request-password-reset',
            'user/reset-password',
            //'admin/*',
            'debug/*',
            'recreacion/restauth/*',
            'recreacion/restconfig/*',
            'recreacion/restcity/*',
            'recreacion/restdashboard/*',
            'recreacion/resthotel/*',
            'recreacion/resthotelagreements/*',
            'recreacion/restroom/*',
            'recreacion/restroomagreements/*',
            'recreacion/restpackage/*',
            'recreacion/restpackageagreements/*',
            'recreacion/restexperences/*',
            'recreacion/restheadquarter/*',
            'recreacion/restlounge/*',
        ]
    ],
    'params' => $params,
    'language' => 'es',
];

if (!YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['*'],
    ];
}

return $config;
