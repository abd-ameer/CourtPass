<?php
/**
 * Minimal router.
 *   $router->get('/venues/{slug}', [VenueController::class, 'show']);
 *   $router->post('/api/bookings', [BookingController::class, 'store'], ['customer']);
 * The optional third argument lists roles allowed; ['*'] means any logged-in user.
 * {params} are passed to the controller method in order.
 */
class Router
{
    private array $routes = [];

    public function get(string $path, array $handler, array $roles = []): void
    {
        $this->add('GET', $path, $handler, $roles);
    }

    public function post(string $path, array $handler, array $roles = []): void
    {
        $this->add('POST', $path, $handler, $roles);
    }

    public function put(string $path, array $handler, array $roles = []): void
    {
        $this->add('PUT', $path, $handler, $roles);
    }

    public function delete(string $path, array $handler, array $roles = []): void
    {
        $this->add('DELETE', $path, $handler, $roles);
    }

    private function add(string $method, string $path, array $handler, array $roles): void
    {
        $pattern = preg_replace('#\{[a-zA-Z_]+\}#', '([^/]+)', '/' . trim($path, '/'));
        $this->routes[] = [
            'method'  => $method,
            'pattern' => '#^' . ($pattern === '/' ? '/' : $pattern) . '$#',
            'handler' => $handler,
            'roles'   => $roles,
        ];
    }

    public function dispatch(Request $request): void
    {
        $method = $request->method();
        // HTML forms can only send GET/POST, so allow <input name="_method" value="DELETE">
        if ($method === 'POST' && in_array(strtoupper((string) $request->input('_method')), ['PUT', 'DELETE'], true)) {
            $method = strtoupper($request->input('_method'));
        }

        $path = $request->path();
        $pathMatched = false;

        foreach ($this->routes as $route) {
            if (!preg_match($route['pattern'], $path, $matches)) {
                continue;
            }
            $pathMatched = true;
            if ($route['method'] !== $method) {
                continue;
            }

            if ($route['roles'] !== []) {
                Auth::guard($route['roles'] === ['*'] ? [] : $route['roles'], $request);
            }

            [$class, $action] = $route['handler'];
            $controller = new $class($request);
            $controller->$action(...array_map('urldecode', array_slice($matches, 1)));
            return;
        }

        $status = $pathMatched ? 405 : 404;
        if ($request->isApi()) {
            Response::json(['error' => $status === 404 ? 'Not found.' : 'Method not allowed.'], $status);
        }
        http_response_code($status);
        View::render('errors/404');
    }
}
