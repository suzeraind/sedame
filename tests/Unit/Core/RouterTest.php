<?php

namespace Tests\Unit\Core;

use App\Core\Container;
use App\Core\Request;
use App\Core\Response;
use App\Core\Router;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * A dummy controller for testing the router.
 */
class TestRouterController
{
    public function index(): Response
    {
        return new Response('TestRouterController index');
    }

    public function withParam(string $name): Response
    {
        return new Response("Hello {$name}");
    }

    public function post(): Response
    {
        return new Response('TestRouterController post');
    }

    public function json(): Response
    {
        return new Response(json_encode(['message' => 'success']), 200, ['Content-Type' => 'application/json']);
    }
}

class RouterTest extends TestCase
{
    private Router $router;

    /**
     * Creates a Router instance and injects a specific set of routes using reflection.
     */
    protected function setUp(): void
    {
        $container = new Container();
        $container->bind(TestRouterController::class, fn() => new TestRouterController());
        $container->bind(Request::class, fn() => Request::createFromGlobals());

        $reflectionClass = new ReflectionClass(Router::class);
        $this->router = $reflectionClass->newInstanceWithoutConstructor();

        // Manually set the container
        $containerProperty = $reflectionClass->getProperty('container');
        $containerProperty->setAccessible(true);
        $containerProperty->setValue($this->router, $container);
    }

    /**
     * @param array<int, array{method: string, path: string, action: string}> $routesConfig
     */
    private function setRoutes(array $routesConfig): void
    {
        $reflectionClass = new ReflectionClass(Router::class);
        $routesProperty = $reflectionClass->getProperty('routes');
        $routesProperty->setAccessible(true);

        $processedRoutes = [
            'GET' => [], 'POST' => [], 'PUT' => [], 'DELETE' => [],
            'PATCH' => [], 'OPTIONS' => [], 'HEAD' => [],
        ];

        $convertMethod = $reflectionClass->getMethod('convertPathToRegex');
        $convertMethod->setAccessible(true);

        foreach ($routesConfig as $route) {
            $pattern = $convertMethod->invoke($this->router, $route['path']);
            $processedRoutes[$route['method']][] = [
                'pattern' => $pattern,
                'path' => $route['path'],
                'action' => $route['action'],
                'middlewares' => [],
            ];
        }

        $routesProperty->setValue($this->router, $processedRoutes);
    }

    private function createRequest(string $method, string $uri): Request
    {
        $server = ['REQUEST_METHOD' => $method, 'REQUEST_URI' => $uri];
        return new Request([], [], [], [], $server);
    }

    public function test_resolves_simple_get_route(): void
    {
        $this->setRoutes([
            ['method' => 'GET', 'path' => '/test', 'action' => TestRouterController::class . '@index']
        ]);
        $request = $this->createRequest('GET', '/test');
        $response = $this->router->dispatch($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatus());
        $response->send();
        $this->expectOutputString('TestRouterController index');
    }

    public function test_resolves_get_route_with_parameter(): void
    {
        $this->setRoutes([
            ['method' => 'GET', 'path' => '/test/{name}', 'action' => TestRouterController::class . '@withParam']
        ]);
        $request = $this->createRequest('GET', '/test/World');
        $response = $this->router->dispatch($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatus());
        $response->send();
        $this->expectOutputString('Hello World');
    }

    public function test_resolves_post_route(): void
    {
        $this->setRoutes([
            ['method' => 'POST', 'path' => '/test-post', 'action' => TestRouterController::class . '@post']
        ]);
        $request = $this->createRequest('POST', '/test-post');
        $response = $this->router->dispatch($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatus());
        $response->send();
        $this->expectOutputString('TestRouterController post');
    }

    public function test_returns_404_for_unknown_route(): void
    {
        $this->setRoutes([]); // No routes
        $request = $this->createRequest('GET', '/non-existent-route');
        $response = $this->router->dispatch($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(404, $response->getStatus());
        $response->send();
        $this->expectOutputString('Page Not Found.');
    }

    public function test_returns_405_for_wrong_method(): void
    {
        $this->setRoutes([
            ['method' => 'GET', 'path' => '/test', 'action' => TestRouterController::class . '@index']
        ]);
        $request = $this->createRequest('POST', '/test');
        $response = $this->router->dispatch($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(405, $response->getStatus());
        $response->send();
        $this->expectOutputString('Method Not Allowed for this URI.');
    }
}