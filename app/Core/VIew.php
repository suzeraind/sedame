<?php

namespace App\Core;

use RuntimeException;

class View
{
    private array $data = [];
    private ?string $layout = null;

    /**
     * Устанавливает переменные для шаблона
     *
     * @param string|array $key
     * @param mixed $value
     * @return $this
     */
    public function with(string|array $key, mixed $value = null): self
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->data[$k] = $v;
            }
        } else {
            $this->data[$key] = $value;
        }

        return $this;
    }

    /**
     * Устанавливает layout
     *
     * @param string $layout
     * @return $this
     */
    public function layout(string $layout): self
    {
        $this->layout = $layout;
        return $this;
    }

    /**
     * Рендерит частичный шаблон
     *
     * @param string $name
     * @param array $data
     * @return void
     */
    public function component(string $name, array $data = []): void
    {
        $path = VIEW_PATH . "/components/{$name}.php";

        if (!file_exists($path)) {
            throw new RuntimeException("Component not found: {$path}");
        }

        extract($data, EXTR_SKIP);
        include $path;
    }

    /**
     * Рендерит основной шаблон и вставляет контент
     *
     * @param string $view
     * @return void
     */
    public function render(string $view): void
    {
        $viewPath = VIEW_PATH . "/{$view}.php";

        if (!file_exists($viewPath)) {
            throw new \Exception("View not found: {$viewPath}");
        }

        extract($this->data, EXTR_SKIP);
        ob_start();
        include $viewPath;
        $content = ob_get_clean();

        if ($this->layout) {
            $layoutPath = VIEW_PATH . "/layouts/{$this->layout}.php";
            if (!file_exists($layoutPath)) {
                throw new \Exception("Layout not found: {$layoutPath}");
            }
            include $layoutPath;
        } else {
            echo $content;
        }
    }
}
