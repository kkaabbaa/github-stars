<?php
use Dotenv\Dotenv;

if (file_exists(__DIR__ . '/../../.env')) {
    $dotenv = new Dotenv(__DIR__ . '/../../');
    $dotenv->overload();

    // database configuration
    $dotenv->required('DB_NAME')->notEmpty();
    $dotenv->required('DB_USER')->notEmpty();
    $dotenv->required('DB_PASS')->notEmpty();
    $dotenv->required('DB_HOST')->notEmpty();

    $dotenv->required('SUPER_USER_TOKEN_REST')->notEmpty();
    $dotenv->required('SUPER_USER_TOKEN_ANSIBLE')->notEmpty();
}

Yii::setAlias('common', dirname(__DIR__));
Yii::setAlias('backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('rest', dirname(dirname(__DIR__)) . '/rest');
Yii::setAlias('console', dirname(dirname(__DIR__)) . '/console');
