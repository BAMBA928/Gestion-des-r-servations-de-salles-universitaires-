<?php

declare(strict_types=1);

use App\render\HtmlRenderer;
use App\render\JsonRenderer;
use App\render\RenderView;
use App\Repository\EloquentReservationRepository;
use App\Repository\EloquentSalleRepository;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use App\Service\AnnulerReservationService;
use App\Service\Conflit;
use App\Service\CreerReservationService;
use App\Service\DateFuture;
use App\Service\DateOrdre;
use App\Service\DureeMaximale;
use App\Service\SalleActive;
use App\Service\AnnulerReservationServiceInterface;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Illuminate\Database\Capsule\Manager as Capsule;

use function DI\autowire;
use function DI\create;
use function DI\factory;
use function DI\get;
use function FastRoute\simpleDispatcher;

return [
    SalleRepositoryInterface::class => autowire(EloquentSalleRepository::class),
    ReservationRepositoryInterface::class => autowire(EloquentReservationRepository::class),


    'ReglesReservation' => [
        get(SalleActive::class),
        get(DateOrdre::class),
        get(DureeMaximale::class),
        get(DateFuture::class),
        get(Conflit::class),
    ],

    CreerReservationService::class => autowire()
        ->constructorParameter('regles', get('ReglesReservation')),

    AnnulerReservationServiceInterface::class => autowire(AnnulerReservationService::class),

    RenderView::class => create()->constructor(get(HtmlRenderer::class), get(JsonRenderer::class)),

    Capsule::class => factory(require __DIR__ . '/capsule.php'),

    Dispatcher::class => factory(function (): Dispatcher {
        return simpleDispatcher(function (RouteCollector $r) {
            (require dirname(__DIR__) . '/routes/web.php')($r);
        });
    }),
];