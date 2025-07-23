<?php

namespace App\Core;

use App\Core\Attributes\Route;

class Router
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
        'PATCH' => [],
        'OPTIONS' => [],
        'HEAD' => [],
    ];

    public function __construct()
    {
        $this->loadRoutes();
        $this->dispatch();
    }

    private function loadRoutes(): void
    {
        $controllersPath = realpath(__DIR__ . '/../Controllers/') . DIRECTORY_SEPARATOR;

        if (!is_dir($controllersPath)) {
            die("Папка контроллеров не найдена: $controllersPath");
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
            $foundFiles[] = $realPath;

            $normalizedPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $realPath);
            $relativePath = substr($normalizedPath, strlen($controllersPath));
            $relativeNamespace = str_replace(DIRECTORY_SEPARATOR, '\\', $relativePath);
            $relativeNamespace = preg_replace('/\.php$/', '', $relativeNamespace);
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
            die("Не найдено ни одного PHP-файла в папке: $controllersPath");
        }
    }

    private function registerAttributes(\ReflectionMethod $method, string $className): void
    {
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
            ];
        }
    }

    private function convertPathToRegex(string $path): string
    {
        $pattern = preg_replace('#\{([a-zA-Z0-9_]+)\}#', '(?P<\1>[^/]+)', $path);
        return '#^' . rtrim($pattern, '/') . '/?$#';
    }


    private function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';

        $uri = rtrim($uri, '/') ?: '/';

        if (!isset($this->routes[$method])) {
            http_response_code(405);
            echo "Метод не поддерживается.";
            return;
        }

        foreach ($this->routes[$method] as $route) {
            if (preg_match($route['pattern'], $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $this->callAction($route['action'], $params);
                return;
            }
        }

        $allowedMethods = [];

        foreach ($this->routes as $m => $routes) {
            foreach ($routes as $route) {
                if (preg_match($route['pattern'], $uri)) {
                    $allowedMethods[] = $m;
                }
            }
        }

        if (!empty($allowedMethods)) {
            http_response_code(405);
            header('Allow: ' . implode(', ', $allowedMethods));
            echo "Метод не разрешён для этого адреса.";
        } else {
            http_response_code(404);
            echo "Страница не найдена.";
        }
    }


    private function callAction(string $action, array $params): void
    {
        [$class, $method] = explode('@', $action);

        if (!class_exists($class)) {
            throw new \Exception("Контроллер не найден: $class");
        }

        $reflectionClass = new \ReflectionClass($class);

        if (!$reflectionClass->hasMethod($method)) {
            throw new \Exception("Метод не найден: $method");
        }

        $controller = $reflectionClass->newInstance();

        $reflectionMethod = $reflectionClass->getMethod($method);
        $arguments = [];

        foreach ($reflectionMethod->getParameters() as $param) {
            $name = $param->getName();
            $arguments[] = $params[$name] ?? null;
        }

        $reflectionMethod->invokeArgs($controller, $arguments);
    }
}
