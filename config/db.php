<?php
/**
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2minimal',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
];
**/
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'sqlite:@app/sqlitedb/yii2minimal.sqlite',
    'username' => 'root',
    'password' => 'yii2minimal@@',
    'charset' => 'utf8',
];
 