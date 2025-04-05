<?php

namespace System\Http;

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class Kernel
{
    public function handle(Request $request): Response
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $routeCollector) {
            $routes = include BASE_PATH . '/routes/web.php';
            foreach ($routes as $route) {
                $routeCollector->addRoute(...$route);
            }
        });

        $routeInfo = $dispatcher->dispatch(
            $request->getMethod(),
            $request->getUri()
        );

        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                return Response::view('errors/404', [], null, 404);

            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                return new Response("Méthode non autorisée", 405);

            case \FastRoute\Dispatcher::FOUND:
                [$status, [$controller, $method], $vars] = $routeInfo;
                return call_user_func_array([new $controller, $method], $vars);

            default:
                return new Response("Erreur serveur", 500);
        }
    }

}
