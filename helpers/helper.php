<?php


if (!function_exists('pp')) {
    /**
     * Pretty print variable with syntax highlighting and optional termination.
     *
     * @param mixed       $data      Variable to dump
     * @param bool        $terminate Whether to call exit after output (default: true)
     * @param string|null $title     Optional title for the dump
     */
    function pp(mixed $data, bool $terminate = true): void
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
        $file = basename((string) $trace['file']);
        $line = (int) $trace['line'];

        $styles = [
            'container' => 'margin:1rem 0;font-family:ui-monospace,monospace;',
            'wrapper' => 'background:#1f2937;color:#f9fafb;border:2px solid #374151;',
            'fileInfo' => 'font-size:.75rem;color:#9ca3af;padding:.5rem 1rem;background:#111827;',
            'codeWrapper' => 'padding:1rem;background:#111827;'
        ];

        $output = var_export($data, true);
        $output = htmlspecialchars($output, ENT_NOQUOTES);

        $output = preg_replace(
            '/\'(?:\\\'|[^\'])*\'/',
            '<span style="color:#34d399;">$0</span>',
            $output
        );

        $output = preg_replace(
            '/\b(\d+(?:\.\d+)?)\b/',
            '<span style="color:#60a5fa;">$0</span>',
            $output
        );

        echo <<<HTML
                <div style="{$styles['container']}">
                    <div style="{$styles['wrapper']}">
                        <div style="{$styles['fileInfo']}">{$file}:{$line}</div>
                        <div style="{$styles['codeWrapper']}">
                            <pre style="margin:0;color:#e5e7eb;font-size:.875rem;line-height:1.5;white-space:pre-wrap;word-break:break-word;">{$output}</pre>
                        </div>
                    </div>
                </div>
            HTML;

        if ($terminate) {
            die();
        }
    }
}

if (!function_exists('asset')) {
    /**
     * Generate a full URL for a public asset.
     *
     * @param  string  $path
     * @return string
     */
    function asset(string $path): string
    {
        $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $url = rtrim("{$scheme}://{$host}", '/');
        return $url . '/' . ltrim($path, '/');
    }
}

// ========================================
// URL & Routing Helpers
// ========================================

if (!function_exists('url')) {
    /**
     * Generate a full URL for a given path.
     *
     * @param  string  $path
     * @return string
     */
    function url(string $path = ''): string
    {
        $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $base = rtrim("{$scheme}://{$host}", '/');
        return $base . '/' . ltrim($path, '/');
    }
}

if (!function_exists('redirect')) {
    /**
     * Redirect to a given URL.
     *
     * @param  string  $url
     * @param  int     $status
     * @return never
     */
    function redirect(string $url, int $status = 302): never
    {
        header("Location: {$url}", true, $status);
        exit;
    }
}

if (!function_exists('back')) {
    /**
     * Redirect back to the previous page.
     *
     * @param  string  $fallback
     * @return never
     */
    function back(string $fallback = '/'): never
    {
        $url = $_SERVER['HTTP_REFERER'] ?? $fallback;
        redirect($url);
    }
}

// ========================================
// View & Response Helpers
// ========================================

if (!function_exists('view')) {
    /**
     * Render a view with optional data and layout.
     *
     * @param  string  $view
     * @param  array   $data
     * @param  string|null  $layout
     * @return \App\Core\Response
     */
    function view(string $view, array $data = [], ?string $layout = null): \App\Core\Response
    {
        $viewInstance = new \App\Core\View();
        $viewInstance->with($data);

        if ($layout) {
            $viewInstance->layout($layout);
        }

        $content = $viewInstance->render($view);
        return new \App\Core\Response($content);
    }
}

if (!function_exists('json')) {
    /**
     * Return a JSON response.
     *
     * @param  mixed  $data
     * @param  int    $status
     * @param  array  $headers
     * @return \App\Core\Response
     */
    function json(mixed $data, int $status = 200, array $headers = []): \App\Core\Response
    {
        $headers['Content-Type'] = 'application/json';
        $content = json_encode($data, JSON_THROW_ON_ERROR);

        $response = new \App\Core\Response($content, $status, $headers);
        return $response;
    }
}

if (!function_exists('response')) {
    /**
     * Create a new response instance.
     *
     * @param  mixed  $content
     * @param  int    $status
     * @param  array  $headers
     * @return \App\Core\Response
     */
    function response(mixed $content = '', int $status = 200, array $headers = []): \App\Core\Response
    {
        return new \App\Core\Response($content, $status, $headers);
    }
}

// ========================================
// Session Helpers
// ========================================

if (!function_exists('session')) {
    /**
     * Get or set session values.
     *
     * @param  string|array|null  $key
     * @param  mixed              $default
     * @return mixed
     */
    function session(string|array|null $key = null, mixed $default = null): mixed
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($key === null) {
            return $_SESSION;
        }

        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $_SESSION[$k] = $v;
            }
            return null;
        }

        return $_SESSION[$key] ?? $default;
    }
}

if (!function_exists('session_flash')) {
    /**
     * Set or get flash session data.
     *
     * @param  string|null  $key
     * @param  mixed        $value
     * @return mixed
     */
    function session_flash(?string $key = null, mixed $value = null): mixed
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($key === null) {
            $flash = $_SESSION['_flash'] ?? [];
            unset($_SESSION['_flash']);
            return $flash;
        }

        if ($value !== null) {
            $_SESSION['_flash'][$key] = $value;
            return null;
        }

        $value = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);
        return $value;
    }
}

if (!function_exists('old')) {
    /**
     * Get old input values from session.
     *
     * @param  string|null  $key
     * @param  mixed        $default
     * @return mixed
     */
    function old(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return session_flash('_old_input') ?? [];
        }

        $oldInput = session_flash('_old_input') ?? [];
        return $oldInput[$key] ?? $default;
    }
}

// ========================================
// Authentication Helpers
// ========================================

if (!function_exists('auth')) {
    /**
     * Get the current authenticated user.
     *
     * @return array|null
     */
    function auth(): ?array
    {
        return \App\Core\Auth::user();
    }
}

if (!function_exists('auth_check')) {
    /**
     * Check if user is authenticated.
     *
     * @return bool
     */
    function auth_check(): bool
    {
        return \App\Core\Auth::check();
    }
}

if (!function_exists('auth_id')) {
    /**
     * Get the current authenticated user ID.
     *
     * @return int|null
     */
    function auth_id(): ?int
    {
        return \App\Core\Auth::id();
    }
}

if (!function_exists('auth_name')) {
    /**
     * Get the current authenticated user name.
     *
     * @return string|null
     */
    function auth_name(): ?string
    {
        return \App\Core\Auth::name();
    }
}

// ========================================
// Request Helpers
// ========================================

if (!function_exists('request')) {
    /**
     * Get request input value.
     *
     * @param  string|null  $key
     * @param  mixed        $default
     * @return mixed
     */
    function request(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $_REQUEST;
        }

        return $_REQUEST[$key] ?? $default;
    }
}

if (!function_exists('method')) {
    /**
     * Get the request method.
     *
     * @return string
     */
    function method(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }
}

if (!function_exists('is_post')) {
    /**
     * Check if request method is POST.
     *
     * @return bool
     */
    function is_post(): bool
    {
        return method() === 'POST';
    }
}

if (!function_exists('is_get')) {
    /**
     * Check if request method is GET.
     *
     * @return bool
     */
    function is_get(): bool
    {
        return method() === 'GET';
    }
}

// ========================================
// String Helpers
// ========================================

if (!function_exists('str_slug')) {
    /**
     * Generate a URL-friendly "slug" from a given string.
     *
     * @param  string  $title
     * @param  string  $separator
     * @return string
     */
    function str_slug(string $title, string $separator = '-'): string
    {
        $title = preg_replace('![^\pL\pN\s]+!u', '', $title);
        $title = preg_replace('/[\s_]+/', $separator, trim($title));
        return strtolower($title);
    }
}

if (!function_exists('str_limit')) {
    /**
     * Limit the number of characters in a string.
     *
     * @param  string  $value
     * @param  int     $limit
     * @param  string  $end
     * @return string
     */
    function str_limit(string $value, int $limit = 100, string $end = '...'): string
    {
        if (mb_strwidth($value, 'UTF-8') <= $limit) {
            return $value;
        }

        return rtrim(mb_strimwidth($value, 0, $limit, '', 'UTF-8')) . $end;
    }
}

if (!function_exists('str_random')) {
    /**
     * Generate a random string.
     *
     * @param  int  $length
     * @return string
     */
    function str_random(int $length = 16): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = '';

        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $string;
    }
}

if (!function_exists('str_contains')) {
    /**
     * Check if a string contains a given substring.
     *
     * @param  string  $haystack
     * @param  string  $needle
     * @return bool
     */
    function str_contains(string $haystack, string $needle): bool
    {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }
}

if (!function_exists('str_starts_with')) {
    /**
     * Check if a string starts with a given substring.
     *
     * @param  string  $haystack
     * @param  string  $needle
     * @return bool
     */
    function str_starts_with(string $haystack, string $needle): bool
    {
        return $needle !== '' && str_starts_with($haystack, $needle);
    }
}

if (!function_exists('str_ends_with')) {
    /**
     * Check if a string ends with a given substring.
     *
     * @param  string  $haystack
     * @param  string  $needle
     * @return bool
     */
    function str_ends_with(string $haystack, string $needle): bool
    {
        return $needle !== '' && substr($haystack, -strlen($needle)) === $needle;
    }
}

// ========================================
// Array Helpers
// ========================================

if (!function_exists('array_get')) {
    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  array   $array
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function array_get(array $array, string $key, mixed $default = null): mixed
    {
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (is_array($array) && array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }

        return $array;
    }
}

if (!function_exists('array_set')) {
    /**
     * Set an array item to a given value using "dot" notation.
     *
     * @param  array   $array
     * @param  string  $key
     * @param  mixed   $value
     * @return array
     */
    function array_set(array &$array, string $key, mixed $value): array
    {
        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $key = array_shift($keys);

            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;

        return $array;
    }
}

if (!function_exists('array_only')) {
    /**
     * Get a subset of the items from the given array.
     *
     * @param  array  $array
     * @param  array  $keys
     * @return array
     */
    function array_only(array $array, array $keys): array
    {
        return array_intersect_key($array, array_flip($keys));
    }
}

if (!function_exists('array_except')) {
    /**
     * Get all of the given array except for a specified array of keys.
     *
     * @param  array  $array
     * @param  array  $keys
     * @return array
     */
    function array_except(array $array, array $keys): array
    {
        return array_diff_key($array, array_flip($keys));
    }
}

// ========================================
// Validation Helpers
// ========================================

if (!function_exists('validate_email')) {
    /**
     * Validate an email address.
     *
     * @param  string  $email
     * @return bool
     */
    function validate_email(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}

if (!function_exists('validate_url')) {
    /**
     * Validate a URL.
     *
     * @param  string  $url
     * @return bool
     */
    function validate_url(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
}

if (!function_exists('sanitize_string')) {
    /**
     * Sanitize a string for safe output.
     *
     * @param  string  $string
     * @return string
     */
    function sanitize_string(string $string): string
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}

// ========================================
// Date & Time Helpers
// ========================================

if (!function_exists('now')) {
    /**
     * Get the current date and time.
     *
     * @param  string|null  $format
     * @return string
     */
    function now(?string $format = null): string
    {
        $format = $format ?? 'Y-m-d H:i:s';
        return date($format);
    }
}

if (!function_exists('today')) {
    /**
     * Get today's date.
     *
     * @param  string  $format
     * @return string
     */
    function today(string $format = 'Y-m-d'): string
    {
        return date($format);
    }
}

if (!function_exists('time_ago')) {
    /**
     * Get human-readable time difference.
     *
     * @param  string  $datetime
     * @return string
     */
    function time_ago(string $datetime): string
    {
        $time = time() - strtotime($datetime);

        return match (true) {
            $time < 60 => 'just now',
            $time < 3600 => floor($time / 60) . ' minutes ago',
            $time < 86400 => floor($time / 3600) . ' hours ago',
            $time < 2592000 => floor($time / 86400) . ' days ago',
            $time < 31536000 => floor($time / 2592000) . ' months ago',
            default => floor($time / 31536000) . ' years ago',
        };
    }
}

// ========================================
// File & Path Helpers
// ========================================

if (!function_exists('storage_path')) {
    /**
     * Get the path to the storage directory.
     *
     * @param  string  $path
     * @return string
     */
    function storage_path(string $path = ''): string
    {
        $storagePath = BASE_PATH . '/storage';
        return $path ? $storagePath . '/' . ltrim($path, '/') : $storagePath;
    }
}

if (!function_exists('public_path')) {
    /**
     * Get the path to the public directory.
     *
     * @param  string  $path
     * @return string
     */
    function public_path(string $path = ''): string
    {
        return $path ? PUBLIC_PATH . '/' . ltrim($path, '/') : PUBLIC_PATH;
    }
}

if (!function_exists('base_path')) {
    /**
     * Get the path to the base directory.
     *
     * @param  string  $path
     * @return string
     */
    function base_path(string $path = ''): string
    {
        return $path ? BASE_PATH . '/' . ltrim($path, '/') : BASE_PATH;
    }
}

if (!function_exists('config_path')) {
    /**
     * Get the path to the config directory.
     *
     * @param  string  $path
     * @return string
     */
    function config_path(string $path = ''): string
    {
        $configPath = BASE_PATH . '/config';
        return $path ? $configPath . '/' . ltrim($path, '/') : $configPath;
    }
}

// ========================================
// Environment Helpers
// ========================================

if (!function_exists('env')) {
    /**
     * Get an environment variable with optional default.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function env(string $key, mixed $default = null): mixed
    {
        $value = $_ENV[$key] ?? getenv($key);

        if ($value === false) {
            return $default;
        }

        // Convert string representations to appropriate types
        return match (strtolower($value)) {
            'true', '(true)' => true,
            'false', '(false)' => false,
            'empty', '(empty)' => '',
            'null', '(null)' => null,
            default => $value,
        };
    }
}

if (!function_exists('app_debug')) {
    /**
     * Check if application is in debug mode.
     *
     * @return bool
     */
    function app_debug(): bool
    {
        return defined('APP_DEBUG') && APP_DEBUG;
    }
}

// ========================================
// Utility Helpers
// ========================================

if (!function_exists('collect')) {
    /**
     * Create a collection from the given value.
     *
     * @param  mixed  $value
     * @return array
     */
    function collect(mixed $value = []): array
    {
        return is_array($value) ? $value : [$value];
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Generate a CSRF token.
     *
     * @return string
     */
    function csrf_token(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['_token'])) {
            $_SESSION['_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_token'];
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Generate a hidden CSRF token field.
     *
     * @return string
     */
    function csrf_field(): string
    {
        $token = csrf_token();
        return "<input type='hidden' name='_token' value='{$token}'>";
    }
}

if (!function_exists('method_field')) {
    /**
     * Generate a hidden HTTP method field.
     *
     * @param  string  $method
     * @return string
     */
    function method_field(string $method): string
    {
        return "<input type='hidden' name='_method' value='{$method}'>";
    }
}

if (!function_exists('abort')) {
    /**
     * Throw an HTTP exception with a given status code.
     *
     * @param  int     $code
     * @param  string  $message
     * @return never
     */
    function abort(int $code, string $message = ''): never
    {
        http_response_code($code);

        if ($message) {
            echo $message;
        } else {
            echo match ($code) {
                404 => 'Page Not Found',
                403 => 'Forbidden',
                401 => 'Unauthorized',
                500 => 'Internal Server Error',
                default => "HTTP Error {$code}",
            };
        }

        exit;
    }
}

if (!function_exists('retry')) {
    /**
     * Retry a callback a given number of times.
     *
     * @param  int      $times
     * @param  callable $callback
     * @param  int      $sleep
     * @return mixed
     */
    function retry(int $times, callable $callback, int $sleep = 0): mixed
    {
        $attempts = 0;

        beginning:
        $attempts++;

        try {
            return $callback();
        } catch (Exception $e) {
            if ($attempts < $times) {
                if ($sleep) {
                    sleep($sleep);
                }
                goto beginning;
            }

            throw $e;
        }
    }
}
