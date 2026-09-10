<?php

declare(strict_types=1);


use Illuminate\Database\Capsule\Manager as Capsule;

return function (): Capsule {

    $config = require __DIR__ . '/database.php';

    $capsule = new Capsule();
    $capsule->addConnection($config);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};
