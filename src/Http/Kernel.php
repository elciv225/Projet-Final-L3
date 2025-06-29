<?php

namespace System\Http;

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class Kernel
{
    public function handle(Request $request): Response
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

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
                return Response::view('errors/404', status: 404);

            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                return new Response("Méthode non autorisée", 405);

            case \FastRoute\Dispatcher::FOUND:
                [$status, [$controllerClass, $method], $vars] = $routeInfo;

                // Instantiate AuthService
                // Ensure AuthService is autoloadable or require it if not using a sophisticated autoloader for app/Services
                $authService = new \App\Services\AuthService();

                // Define protected route patterns and public exceptions
                $currentUri = $request->getUri();
                $isProtectedRoute = false;

                // Example: Protect routes starting with /index (admin area)
                if (strpos($currentUri, '/index') === 0) {
                    $isProtectedRoute = true;
                }
                // Example: Protect routes starting with /espace-commission
                if (strpos($currentUri, '/espace-commission') === 0) {
                    $isProtectedRoute = true;
                }
                // Add other protected patterns as needed

                // Define public routes that should not be protected even if they match a pattern (e.g. /index/public_page)
                $publicExceptions = [
                    // '/index/public_sub_page', // Example
                ];
                if (in_array($currentUri, $publicExceptions)) {
                    $isProtectedRoute = false;
                }

                // Routes explicitly for authentication should not be protected themselves
                $authRoutes = ['/login', '/register', '/authentification', '/logout'];
                if (in_array($currentUri, $authRoutes)) {
                    $isProtectedRoute = false;
                }


                if ($isProtectedRoute && !$authService->isAuthenticated()) {
                    // Store intended URL in session to redirect after login (optional)
                    if ($request->getMethod() === 'GET') {
                       $_SESSION['intended_url'] = $currentUri;
                    }
                    return Response::redirect('/login');
                }

                // If authenticated or not a protected route, proceed to controller
                $controllerInstance = new $controllerClass();
                return call_user_func_array([$controllerInstance, $method], $vars);

            default:
                return new Response("Erreur serveur", 500);
        }
    }

}
