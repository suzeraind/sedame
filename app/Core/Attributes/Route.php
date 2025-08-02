<?php

namespace App\Core\Attributes;

#[\Attribute(
    flags: \Attribute::IS_REPEATABLE | \Attribute::TARGET_METHOD
)]
class Route
{
    /**
     * @param string $method HTTP methods, e.g., 'GET', 'POST', 'DELETE'
     * @param string $path URL path
     */
    public function __construct(
        public string $method,
        public string $path
    ) {
    }
}
