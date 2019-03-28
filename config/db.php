<?php

return [
    // 'class' => 'yii\db\Connection',
    // 'dsn' => 'mysql:host=localhost;dbname=yii-colsubsidio',
    // 'username' => 'root',
    // 'password' => '',
    // // 'username' => 'coladmin',
    // // 'password' => '123456!aA',
    // 'charset' => 'utf8',

	'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=colbackendqa.mysql.database.azure.com;port=3306;dbname=yii-colsubsidio',
    'username' => 'coladmin@colbackendqa',
    'password' => '1234567890aA!',
    'charset' => 'utf8',
	
    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
