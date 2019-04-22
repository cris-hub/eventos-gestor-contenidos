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
    'dsn' => getenv('MYSQL_HOTELES_CONECTION_PHP'),
    'username' => getenv('MYSQL_HOTELES_USER'),
    'password' => '123456789!aA',
    'charset' => 'utf8',
	
    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
