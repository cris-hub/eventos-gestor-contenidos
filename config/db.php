<?php

return [
	'class' => 'yii\db\Connection',
    'dsn' => getenv('MYSQL_CONECTION_PHP','mysql:host=127.0.0.1;port=3306;dbname=yii-colsubsidio'),
    'username' => getenv('MYSQL_USER','root'),
    'password' => getenv('MYSQL_PASSWORD','123456789!aA'),
    'charset' => 'utf8',
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 3600,
    'schemaCache' => 'cache',
];
