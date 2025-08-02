<?php

namespace App\Core;

use RuntimeException;

class View
{
    /**
     * @var array<string, mixed>
     */
    private array $data = [];

    /**
     * @var string|null
     */
    private ?string $layout = null;

    /**
     * Set variables for the view.
     *
     * @param string|array<string, mixed> $key
     * @param mixed $value
     * @return $this
     */
    public function with(string|array $key, mixed $value = null): self
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }

        return $this;
    }

    /**
     * Set the layout for the view.
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
     * Render a view component.
     *
     * @param string $name
     * @param array<string, mixed> $data
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
     * Render the main view and insert content.
     *
     * @param string $view
     * @return void
     * @throws \Exception
     */
    public function render(string $view): void
    {
        $viewPath = VIEW_PATH . "/pages/{$view}.php";

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