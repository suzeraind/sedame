<?php

namespace App\Core\Attributes;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_CLASS)]
class Middleware
{
    public function __construct(
        public string|array $middlewares
    ) {
        $this->middlewares = is_array($middlewares) ? $middlewares : [$middlewares];
    }
}
