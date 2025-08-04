<?php

namespace Tests\Unit\Core;

use App\Core\Container;
use App\Core\Router;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * A dummy controller for testing the router.
 * It is defined here to avoid polluting the main application's controller directory.
 */
class TestRouterController
{
    public function index(): void
    {
        echo 'TestRouterController index';
    }

    public function withParam(string $name): void
    {
        echo "Hello {$name}";
    }

    public function post(): void
    {
        echo 'TestRouterController post';
    }

    public function json(): void
    {
        header('Content-Type: application/json');
        echo json_encode(['message' => 'success']);
    }
}

class RouterTest extends TestCase
{
    /**
     * Creates a Router instance and injects a specific set of routes using reflection.
     * This bypasses the constructor's file scanning and allows for isolated testing.
     *
     * @param array<int, array{method: string, path: string, action: string}> $routesConfig
     * @return Router
     * @throws \ReflectionException
     */
    private function createRouterWithRoutes(array $routesConfig): Router
    {
        $container = new Container();
        $container->bind(TestRouterController::class, fn() => new TestRouterController());

        $reflectionClass = new ReflectionClass(Router::class);
        $router = $reflectionClass->newInstanceWithoutConstructor();

        // Manually set the container
        $containerProperty = $reflectionClass->getProperty('container');
        $containerProperty->setAccessible(true);
        $containerProperty->setValue($router, $container);

        $routesProperty = $reflectionClass->getProperty('routes');
        $routesProperty->setAccessible(true);

        $processedRoutes = [
            'GET' => [],
            'POST' => [],
            'PUT' => [],
            'DELETE' => [],
            'PATCH' => [],
            'OPTIONS' => [],
            'HEAD' => [],
        ];

        $convertMethod = $reflectionClass->getMethod('convertPathToRegex');
        $convertMethod->setAccessible(true);

        foreach ($routesConfig as $route) {
            $pattern = $convertMethod->invoke($router, $route['path']);
            $processedRoutes[$route['method']][] = [
                'pattern' => $pattern,
                'path' => $route['path'],
                'action' => $route['action'],
            ];
        }

        $routesProperty->setValue($router, $processedRoutes);

        return $router;
    }

    /**
     * Dispatches the router for a given method and URI and captures the output.
     *
     * @param Router $router
     * @param string $method
     * @param string $uri
     * @return string
     * @throws \ReflectionException
     */
    private function dispatchRouter(Router $router, string $method, string $uri): string
    {
        $_SERVER['REQUEST_METHOD'] = $method;
        $_SERVER['REQUEST_URI'] = $uri;

        $reflectionClass = new ReflectionClass(Router::class);
        $dispatchMethod = $reflectionClass->getMethod('dispatch');
        $dispatchMethod->setAccessible(true);

        ob_start();
        $dispatchMethod->invoke($router);
        return ob_get_clean() ?: '';
    }

    public function test_resolves_simple_get_route(): void
    {
        $routes = [
            ['method' => 'GET', 'path' => '/test', 'action' => TestRouterController::class . '@index']
        ];
        $router = $this->createRouterWithRoutes($routes);
        $output = $this->dispatchRouter($router, 'GET', '/test');
        $this->assertEquals('TestRouterController index', $output);
    }

    public function test_resolves_get_route_with_parameter(): void
    {
        $routes = [
            ['method' => 'GET', 'path' => '/test/{name}', 'action' => TestRouterController::class . '@withParam']
        ];
        $router = $this->createRouterWithRoutes($routes);
        $output = $this->dispatchRouter($router, 'GET', '/test/World');
        $this->assertEquals('Hello World', $output);
    }

    public function test_resolves_post_route(): void
    {
        $routes = [
            ['method' => 'POST', 'path' => '/test-post', 'action' => TestRouterController::class . '@post']
        ];
        $router = $this->createRouterWithRoutes($routes);
        $output = $this->dispatchRouter($router, 'POST', '/test-post');
        $this->assertEquals('TestRouterController post', $output);
    }

    public function test_returns_404_for_unknown_route(): void
    {
        $router = $this->createRouterWithRoutes([]); // No routes
        $output = $this->dispatchRouter($router, 'GET', '/non-existent-route');
        $this->assertEquals('Page Not Found.', $output);
    }

    public function test_returns_405_for_wrong_method(): void
    {
        $routes = [
            ['method' => 'GET', 'path' => '/test', 'action' => TestRouterController::class . '@index']
        ];
        $router = $this->createRouterWithRoutes($routes);
        $output = $this->dispatchRouter($router, 'POST', '/test');
        $this->assertEquals('Method Not Allowed for this URI.', $output);
    }
}
