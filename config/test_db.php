<?php
$db = require __DIR__ . '/db.php';
// test database! Important not to run tests on production or development databases
$db['dsn'] = 'mysql:host=localhost;dbname=yii2_basic_tests';
$db['username'] = 'root';
    $db['password'] = 'root';
    $db['charset'] = 'utf8';

return $db;
