<?php


namespace App\Core;

/**
 * The main application class.
 *
 * This class is responsible for bootstrapping the application, managing the
 * service container, and handling the request-response lifecycle.
 *
 * @method static mixed get(string $key)
 * @method static void bind(string $key, mixed $value)
 */
class Application
{
    /**
     * The service container instance.
     *
     * @var Container
     */
    public readonly Container $container;

    /**
     * Create a new application instance.
     */
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->container = new Container;
        $this->registerBindings();
        Facade::setFacadeContainer($this->container);
    }

    /**
     * Register the core bindings into the container.
     *
     * @return void
     */
    protected function registerBindings(): void
    {
        $this->container->bind(Application::class, fn() => $this);
        $this->container->bind(Database::class, fn() => Database::getInstance());
        $this->container->bind(View::class, fn() => new View());
    }

    /**
     * Run the application and send the response.
     *
     * This method handles the incoming request, dispatches it through the
     * router, and sends the resulting response to the client.
     *
     * @return void
     */
    public function run(): void
    {
        try {
            $request = Request::createFromGlobals();
            $this->container->bind(Request::class, fn() => $request);

            $router = new Router($this->container);
            $response = $router->dispatch($request);
        } catch (\Throwable $e) {
            $response = $this->handleException($e);
        }

        $response->send();
    }

    /**
     * Handle a thrown exception.
     *
     * @param \Throwable $e The thrown exception.
     * @return Response The response to send to the client.
     */
    protected function handleException(\Throwable $e): Response
    {
        if (APP_DEBUG) {
            // In a real-world scenario, you might use a more sophisticated
            // error display library like Whoops.
            $message = '<pre>';
            $message .= '<strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '<br>';
            $message .= '<strong>File:</strong> ' . $e->getFile() . '<br>';
            $message .= '<strong>Line:</strong> ' . $e->getLine() . '<br>';
            $message .= '<strong>Trace:</strong><br>' . htmlspecialchars($e->getTraceAsString());
            $message .= '</pre>';

            return new Response($message, 500);
        }

        return new Response('<h1>500 - Internal Server Error</h1>', 500);
    }
}
