<?php

declare(strict_types=1);

namespace App;

use DI\Container;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;

use function FastRoute\simpleDispatcher;

final class Application
{
    public function __construct(
        private Container $container,
    ) {
    }

    public function run(): void
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $r) {
            (require dirname(__DIR__) . '/routes/web.php')($r);
        });

        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        // supprimer la query string avant le dispatch
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                http_response_code(404);
                require dirname(__DIR__) . '/templates/error/404.php';
                break;

            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                http_response_code(405);
                header('Allow: ' . implode(', ', $allowedMethods));
                require dirname(__DIR__) . '/templates/error/405.php';
                break;

            case Dispatcher::FOUND:
                [$class, $method] = $routeInfo[1];
                $vars = $routeInfo[2];

                $controller = $this->container->get($class);
                $controller->$method(...array_values($vars));
                break;
        }
    }
}