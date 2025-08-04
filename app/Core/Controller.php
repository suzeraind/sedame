<?php

namespace App\Core;

abstract class Controller
{
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

