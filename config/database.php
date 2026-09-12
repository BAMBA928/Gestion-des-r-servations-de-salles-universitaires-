<?php

declare(strict_types=1);

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

function env_value(string $key, mixed $default = null): mixed
{
    return $_ENV[$key] ?? getenv($key) ?: $default;
}

$options = [];

if (!empty(env_value('DB_SSL_CA'))) {
    $options[PDO::MYSQL_ATTR_SSL_CA] = env_value('DB_SSL_CA');
    $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
}

return [
    'driver'    => env_value('DB_DRIVER', 'mysql'),
    'host'      => env_value('DB_HOST', '127.0.0.1'),
    'port'      => env_value('DB_PORT', '3306'),
    'database'  => env_value('DB_DATABASE', ''),
    'username'  => env_value('DB_USERNAME', ''),
    'password'  => env_value('DB_PASSWORD', ''),
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
    'options'   => $options,
];