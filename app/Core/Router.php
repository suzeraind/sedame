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

            $this->routes[$methodType][$route->path] = "$className@{$method->getName()}";
        }
    }

    private function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';

        $supportedMethods = array_keys($this->routes);

        if (!in_array($method, $supportedMethods)) {
            http_response_code(405);
            header('Allow: ' . implode(', ', $supportedMethods));
            echo "Метод не поддерживается.";
            return;
        }

        if (isset($this->routes[$method][$uri])) {
            $this->callAction($this->routes[$method][$uri], []);
            return;
        }

        $allowed = array_filter($supportedMethods, fn($m) => isset($this->routes[$m][$uri]));

        if (!empty($allowed)) {
            http_response_code(405);
            header('Allow: ' . implode(', ', $allowed));
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

        $controller = new $class();

        if (!method_exists($controller, $method)) {
            throw new \Exception("Метод не найден: $method");
        }

        $controller->$method(...$params);
    }
}
