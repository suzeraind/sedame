<?php

namespace App\Core\Attributes;

#[\Attribute]
class Route
{
    /**
     * @param string $method HTTP-методы, например: 'GET', 'POST', 'DELETE'
     * @param string $path URL-путь
     */
    public function __construct(
        public string $method,
        public string $path
    ) {
    }
}
