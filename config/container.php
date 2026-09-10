<?php

declare(strict_types=1);

use App\Application;
use App\Console\MigrateCommand;
use App\Console\SeedCommand;
use App\Controller\ReservationController;
use App\Controller\SalleController;
use App\Repository\EloquentReservationRepository;
use App\Repository\EloquentSalleRepository;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use App\Service\AnnulerReservationService;
use App\Service\CreerReservationService;
use App\Validation\ReservationValidator;
use App\Validation\SalleValidator;
use App\Service\SalleActive;
use App\Service\DateOrdre;
use App\Service\DureeMaximale;
use App\Service\DateFuture;
use App\Service\Conflit;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Illuminate\Database\Capsule\Manager as Capsule;




use function DI\autowire;
use function DI\factory;
use function FastRoute\simpleDispatcher;
use function DI\get;

return [
    SalleRepositoryInterface::class => autowire(EloquentSalleRepository::class),
    ReservationRepositoryInterface::class => autowire(EloquentReservationRepository::class),

    SalleValidator::class => autowire(),
    ReservationValidator::class => autowire(),
    
    'Exceptions' => [

        autowire(SalleActive::class),
        autowire(DateOrdre::class),
        autowire(DureeMaximale::class),
        autowire(DateFuture::class),
        autowire(Conflit::class),
    ],

    CreerReservationService::class => autowire()
        ->constructorParameter('Exceptions', get('Exceptions')),

    AnnulerReservationService::class => autowire(),
    SalleController::class => autowire(),
    ReservationController::class => autowire(),
    Application::class => autowire(),
    MigrateCommand::class => autowire(),
    SeedCommand::class => autowire(),

    Capsule::class => factory(
        require __DIR__ . '/capsule.php'
    ),
    Dispatcher::class => factory(function (): Dispatcher {
        return simpleDispatcher(function (RouteCollector $r) {
            (require dirname(__DIR__) . '/routes/web.php')($r);
        });
    }),
];
