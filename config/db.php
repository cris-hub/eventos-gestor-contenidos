<?php

return [
	'class' => 'yii\db\Connection',
    'dsn' => getenv('MYSQL_CONECTION_PHP'),
    'username' => getenv('MYSQL_USER'),
    'password' => getenv('MYSQL_PASSWORD'),
    'charset' => 'utf8',
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 3600,
    'schemaCache' => 'cache',
];
