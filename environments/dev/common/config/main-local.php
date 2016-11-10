<?php
/**
 * Local config for developer of environment.
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */

return [
    'language' => 'en',
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host='. (getenv('DB_HOST')?:'127.0.0.1') .';dbname='. (getenv('DB_NAME')?:'pacific-mock'),
            'username' => getenv('DB_USER')?:'astro',
            'password' => getenv('DB_PASS')?:'123',
            'charset' => 'utf8',
        ],
    ],
];
