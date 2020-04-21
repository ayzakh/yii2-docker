<?php

return [
    'class' => 'yii\db\Connection',
    // 'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'dsn' => "pgsql:host={$_ENV['NETWORK_PG']};dbname={$_ENV['PG_DB']}",
    'username' => 'postgres',
    'password' => $_ENV['PG_PWD'],
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
