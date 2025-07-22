<?php

namespace App\Controllers;

abstract class Controller
{

    /**
     * Рендерит вид и оборачивает его в основной шаблон
     *
     * @param string $view Путь к файлу вида (без расширения)
     * @param array $data Данные для передачи в шаблон
     * @return void
     */
    protected function render(string $view, array $data = [], $layout = 'main'): void
    {
        $viewPath = VIEW_PATH . "/{$view}.php";

        if (!file_exists($viewPath)) {
            throw new \Exception("Шаблон не найден: {$viewPath}");
        }

        ob_start();
        extract($data);
        include $viewPath;
        $content = ob_get_clean();

        include VIEW_PATH . "/layouts/{$layout}.php";
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
     * Возвращает JSON-ответ
     *
     * @param array $data
     * @return void
     */
    protected function json(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
