<?php

namespace App\Core;

use App\Core\View;

abstract class Controller
{
    /**
     * @var View
     */
    protected View $view;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->view = new View();
    }

    /**
     * Render a view with data and a layout.
     *
     * @param string $view
     * @param array<string, mixed>|null $data
     * @param string|null $layout
     * @return void
     */
    protected function render(string $view, ?array $data = [], ?string $layout = 'main'): void
    {
        $this->view
            ->with($data ?? [])
            ->layout($layout)
            ->render($view);
    }

    /**
     * Get the view instance.
     *
     * @return View
     */
    protected function view(): View
    {
        return $this->view;
    }

    /**
     * Redirect to a different URL.
     *
     * @param string $url
     * @return never
     */
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    /**
     * Return data as JSON.
     *
     * @param array<string, mixed> $data
     * @return never
     */
    protected function json(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

