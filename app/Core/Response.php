<?php

namespace App\Core;

/**
 * Represents an HTTP response.
 *
 * Encapsulates the content, status code, and headers of an HTTP response.
 */
class Response
{
    /**
     * Response constructor.
     *
     * @param mixed $content The response content.
     * @param int $status The response status code.
     * @param array<string, string> $headers An array of response headers.
     */
    public function __construct(
        protected mixed $content,
        protected int $status = 200,
        protected array $headers = []
    ) {
    }

    /**
     * Creates a new Response instance.
     *
     * @param mixed $content The response content.
     * @param int $status The response status code.
     * @param array<string, string> $headers An array of response headers.
     * @return static
     */
    public static function create(mixed $content = '', int $status = 200, array $headers = []): static
    {
        return new static($content, $status, $headers);
    }

    /**
     * Gets the response status code.
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Sends the response to the client.
     *
     * This method sends the headers and content to the output buffer.
     * @return void
     */
    public function send(): void
    {
        http_response_code($this->status);

        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        echo $this->content;
    }

    /**
     * Sets the response content.
     *
     * @param mixed $content The content to set.
     * @return static
     */
    public function setContent(mixed $content): static
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Sets the response status code.
     *
     * @param int $status The status code to set.
     * @return static
     */
    public function setStatus(int $status): static
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Adds a header to the response.
     *
     * @param string $name The header name.
     * @param string $value The header value.
     * @return static
     */
    public function withHeader(string $name, string $value): static
    {
        $this->headers[$name] = $value;
        return $this;
    }
}
