<?php

declare(strict_types=1);

use App\Application;
use DI\ContainerBuilder;
use Illuminate\Database\Capsule\Manager as Capsule;

require dirname(__DIR__) . '/vendor/autoload.php';

$builder = new ContainerBuilder();
$builder->addDefinitions(dirname(__DIR__) . '/config/container.php');
$container = $builder->build();

$container->get(Capsule::class);

$application = $container->get(Application::class);
$application->run();