<?php

namespace App\Core;

/**
 * Represents an HTTP request.
 *
 * Provides an object-oriented way to access HTTP request data.
 */
class Request
{
    /**
     * Request constructor.
     *
     * @param array<string, mixed> $query The GET parameters.
     * @param array<string, mixed> $request The POST parameters.
     * @param array<string, mixed> $cookies The COOKIE parameters.
     * @param array<string, mixed> $files The FILES parameters.
     * @param array<string, mixed> $server The SERVER parameters.
     */
    public function __construct(
        public readonly array $query,
        public readonly array $request,
        public readonly array $cookies,
        public readonly array $files,
        public readonly array $server
    ) {
    }

    /**
     * Creates a new request from PHP's global variables.
     *
     * @return static
     */
    public static function createFromGlobals(): static
    {
        return new static($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
    }

    /**
     * Gets the request method.
     *
     * @return string
     */
    public function method(): string
    {
        return $this->server['REQUEST_METHOD'] ?? 'GET';
    }

    /**
     * Gets the request URI path.
     *
     * @return string
     */
    public function uri(): string
    {
        return strtok($this->server['REQUEST_URI'] ?? '', '?') ?: '/';
    }

    /**
     * Gets an input value from the request.
     *
     * It checks POST data first, then GET data.
     *
     * @param string $key The input key.
     * @param mixed|null $default The default value if the key is not found.
     * @return mixed
     */
    public function input(string $key, mixed $default = null): mixed
    {
        return $this->request[$key] ?? $this->query[$key] ?? $default;
    }

    /**
     * Gets all input data from the request (GET and POST).
     *
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return array_merge($this->query, $this->request);
    }
}
