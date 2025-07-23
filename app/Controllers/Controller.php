<?php

namespace App\Controllers;

use App\Core\View;

abstract class Controller
{
    public function __construct(
        protected View $view = new View
    ) {
    }

    /**
     * Рендер необходимого компонента
     * 
     * @param string $view
     * @param mixed $data
     * @param mixed $layout
     * @return void
     */
    protected function render(string $view, ?array $data = [], ?string $layout = 'main'): void
    {
        $this->view
            ->with($data)
            ->layout($layout)
            ->render($view);
    }

    /**
     * Возвращает прямой доступ к View
     * 
     * @return View
     */
    protected function view(): View
    {
        return $this->view;
    }

    /**
     * Перенаправление на другой URL
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
     *  Возвращает данные в JSON формате
     * 
     * @param array $data
     * @return never
     */
    protected function json(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
