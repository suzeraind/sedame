<?php

namespace App\Core;

use App\Core\Attributes\Route;
use App\Core\Attributes\Middleware;

class Router
{
    /**
     * @var array<string, array<int, array{pattern: string, path: string, action: string, middlewares: array}>>
     */
    private array $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
        'PATCH' => [],
        'OPTIONS' => [],
        'HEAD' => [],
    ];

    /**
     * Router constructor.
     *
     * Loads routes and dispatches the current request.
     */
    public function __construct()
    {
        $this->loadRoutes();
        $this->dispatch();
    }

    /**
     * Loads all routes from controller files.
     *
     * This method scans the Controllers directory, finds all controller classes,
     * and registers routes based on the Route attributes in the controller methods.
     *
     * @return void
     */
    private function loadRoutes(): void
    {
        $controllersPath = realpath(__DIR__ . '/../Controllers/') . DIRECTORY_SEPARATOR;

        if (!is_dir($controllersPath)) {
            die("Controllers directory not found: $controllersPath");
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($controllersPath)
        );

        $foundFiles = [];

        foreach ($iterator as $file) {
            if ($file->isDir() || $file->getExtension() !== 'php') {
                continue;
            }

            $realPath = $file->getRealPath();
            if ($realPath === false) {
                continue;
            }
            $foundFiles[] = $realPath;

            $normalizedPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $realPath);
            $relativePath = substr($normalizedPath, strlen($controllersPath));
            $relativeNamespace = str_replace(DIRECTORY_SEPARATOR, '\\', $relativePath);
            $relativeNamespace = preg_replace('/\\.php$/', '', $relativeNamespace);
            $className = 'App\\Controllers\\' . $relativeNamespace;

            if (!class_exists($className)) {
                continue;
            }

            $reflection = new \ReflectionClass($className);

            foreach ($reflection->getMethods() as $method) {
                $this->registerAttributes($method, $className);
            }
        }

        if (empty($foundFiles)) {
            die("No PHP files found in directory: $controllersPath");
        }
    }

    /**
     * Registers routes from a method's attributes.
     *
     * @param \ReflectionMethod $method
     * @param string $className
     * @return void
     */
    private function registerAttributes(\ReflectionMethod $method, string $className): void
    {
        $middlewares = $this->extractMiddlewares($method);

        foreach ($method->getAttributes(Route::class) as $attr) {
            $route = $attr->newInstance();
            $methodType = strtoupper(trim($route->method));

            if (!isset($this->routes[$methodType])) {
                continue;
            }

            $pattern = $this->convertPathToRegex($route->path);

            $this->routes[$methodType][] = [
                'pattern' => $pattern,
                'path' => $route->path,
                'action' => "$className@{$method->getName()}",
                'middlewares' => $middlewares,
            ];
        }
    }

    /**
     * Extracts middleware attributes from a method.
     *
     * @param \ReflectionMethod $method
     * @return array
     */
    private function extractMiddlewares(\ReflectionMethod $method): array
    {
        $middlewares = [];

        foreach ($method->getAttributes(Middleware::class) as $attr) {
            $middleware = $attr->newInstance();
            $middlewares = array_merge($middlewares, (array) $middleware->middlewares);
        }

        return $middlewares;
    }

    /**
     * Converts a route path to a regex pattern.
     *
     * @param string $path
     * @return string
     */
    private function convertPathToRegex(string $path): string
    {
        $pattern = preg_replace('#\\{([a-zA-Z0-9_]+)\\}#', '(?P<\\1>[^/]+)', $path);
        return '#^' . rtrim($pattern, '/') . '/?$#';
    }

    /**
     * Dispatches the request to the appropriate route.
     *
     * @return void
     */
    private function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
        $uri = rtrim($uri, '/') ?: '/';

        if (!isset($this->routes[$method])) {
            http_response_code(405);
            echo "Method Not Allowed.";
            return;
        }

        foreach ($this->routes[$method] as $route) {
            if (preg_match($route['pattern'], $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                if (!$this->runMiddlewares($route['middlewares'])) {
                    return; 
                }

                $this->callAction($route['action'], $params);
                return;
            }
        }

        $this->handleNotFound($uri);
    }

    /**
     * Runs middleware chain.
     *
     * @param array $middlewares
     * @return bool
     */
    private function runMiddlewares(array $middlewares): bool
    {
        foreach ($middlewares as $middleware) {
            $middlewareClass = $this->resolveMiddlewareClass($middleware);

            if (class_exists($middlewareClass)) {
                $instance = new $middlewareClass();

                if (method_exists($instance, 'handle')) {
                    $result = $instance->handle();

                    if ($result === false) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     * Resolves middleware class name.
     *
     * @param string $middleware
     * @return string
     */
    private function resolveMiddlewareClass(string $middleware): string
    {
        if (class_exists($middleware)) {
            return $middleware;
        }

        return "App\\Core\\Middleware\\{$middleware}";
    }

    /**
     * Handles the case where no route is found.
     *
     * @param string $uri
     * @return void
     */
    private function handleNotFound(string $uri): void
    {
        $allowedMethods = [];
        foreach ($this->routes as $method => $routes) {
            foreach ($routes as $route) {
                if (preg_match($route['pattern'], $uri)) {
                    $allowedMethods[] = $method;
                }
            }
        }

        if (!empty($allowedMethods)) {
            http_response_code(405);
            header('Allow: ' . implode(', ', array_unique($allowedMethods)));
            echo "Method Not Allowed for this URI.";
        } else {
            http_response_code(404);
            echo "Page Not Found.";
        }
    }

    /**
     * Calls the action associated with a route.
     *
     * @param string $action
     * @param array<string, mixed> $params
     * @return void
     * @throws \Exception
     */
    private function callAction(string $action, array $params): void
    {
        [$class, $method] = explode('@', $action);

        if (!class_exists($class)) {
            throw new \Exception("Controller not found: $class");
        }

        $reflectionClass = new \ReflectionClass($class);

        if (!$reflectionClass->hasMethod($method)) {
            throw new \Exception("Method not found: $method in class $class");
        }

        $controller = $reflectionClass->newInstance();

        $reflectionMethod = $reflectionClass->getMethod($method);
        $arguments = [];

        foreach ($reflectionMethod->getParameters() as $param) {
            $name = $param->getName();
            $type = $param->getType();

            if (isset($params[$name])) {
                if ($type instanceof \ReflectionNamedType) {
                    $arguments[] = match ($type->getName()) {
                        'int' => (int) $params[$name],
                        'float' => (float) $params[$name],
                        'bool' => (bool) $params[$name],
                        default => $params[$name],
                    };
                } else {
                    $arguments[] = $params[$name];
                }
            } elseif ($param->isDefaultValueAvailable()) {
                $arguments[] = $param->getDefaultValue();
            } else {
                $arguments[] = null;
            }
        }

        $reflectionMethod->invokeArgs($controller, $arguments);
    }
}
