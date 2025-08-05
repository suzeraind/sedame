<?php

namespace App\Core;

use App\Core\Attributes\Route;
use App\Core\Attributes\Middleware;
use App\Core\Contracts\IMiddleware;
use App\Core\Container;
use App\Core\Request;
use App\Core\Response;

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
     */
    public function __construct(private Container $container)
    {
        $this->loadRoutes();
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
     * @param Request $request
     * @return Response
     */
    public function dispatch(Request $request): Response
    {
        $method = $request->method();
        $uri = $request->uri();
        $uri = rtrim($uri, '/') ?: '/';

        if (!isset($this->routes[$method])) {
            return new Response('Method Not Allowed.', 405);
        }

        foreach ($this->routes[$method] as $route) {
            if (preg_match($route['pattern'], $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                if (!$this->runMiddlewares($route['middlewares'] ?? [])) {
                    return new Response('Forbidden', 403);
                }

                return $this->callAction($route['action'], $params);
            }
        }

        return $this->handleNotFound($uri);
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

                if ($instance instanceof IMiddleware) {
                    if ($instance->handle() === false) {
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
     * @return Response
     */
    private function handleNotFound(string $uri): Response
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
            return new Response('Method Not Allowed for this URI.', 405, ['Allow' => implode(', ', array_unique($allowedMethods))]);
        } else {
            return new Response('Page Not Found.', 404);
        }
    }

    /**
     * Calls the action associated with a route.
     *
     * @param string $action
     * @param array<string, mixed> $params
     * @return Response
     * @throws \Exception
     */
    private function callAction(string $action, array $params): Response
    {
        [$class, $method] = explode('@', $action);

        if (!class_exists($class)) {
            throw new \Exception("Controller not found: $class");
        }

        $controller = $this->container->resolve($class);

        $reflectionClass = new \ReflectionClass($class);

        if (!$reflectionClass->hasMethod($method)) {
            throw new \Exception("Method not found: $method in class $class");
        }

        $reflectionMethod = $reflectionClass->getMethod($method);
        $arguments = [];

        foreach ($reflectionMethod->getParameters() as $param) {
            $name = $param->getName();
            $type = $param->getType();

            if ($type instanceof \ReflectionNamedType && $type->getName() === Request::class) {
                $arguments[] = $this->container->resolve(Request::class);
                continue;
            }

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

        $response = $reflectionMethod->invokeArgs($controller, $arguments);

        if (!$response instanceof Response) {
            if (is_string($response) || is_array($response) || is_object($response)) {
                return new Response($response);
            }
            throw new \Exception('Action must return a Response object');
        }

        return $response;
    }
}
