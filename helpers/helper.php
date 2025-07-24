<?php


if (!function_exists('pp')) {
    /**
     * Pretty print variable with syntax highlighting and optional termination
     *
     * @param mixed       $data      Variable to dump
     * @param bool        $terminate Whether to call exit after output
     * @param string|null $title     Optional dump title
     */
    function pp(mixed $data, bool $terminate = true, ?string $title = null): void
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
        $file = basename((string) $trace['file']);
        $line = (int) $trace['line'];

        $styles = [
            'container' => 'margin:1rem 0;font-family:ui-monospace,monospace;',
            'title' => 'background:#2563eb;color:#fff;padding:.5rem 1rem;font-weight:700;',
            'wrapper' => 'background:#1f2937;color:#f9fafb;border:2px solid #374151;',
            'fileInfo' => 'font-size:.75rem;color:#9ca3af;padding:.5rem 1rem;background:#111827;',
            'codeWrapper' => 'padding:1rem;background:#111827;'
        ];

        $titleHtml = $title
            ? sprintf('<div style="%s">%s</div>', $styles['title'], htmlspecialchars($title, ENT_QUOTES))
            : '';

        $wrapperStyle = $styles['wrapper'] . ($title ? ' border-top:none;' : '');

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

        echo sprintf(
            '<div style="%s">%s<div style="%s"><div style="%s">%s:%d</div><div style="%s"><pre style="margin:0;color:#e5e7eb;font-size:.875rem;line-height:1.5;white-space:pre-wrap;word-break:break-word;">%s</pre></div></div></div>',
            $styles['container'],
            $titleHtml,
            $wrapperStyle,
            $styles['fileInfo'],
            $file,
            $line,
            $styles['codeWrapper'],
            $output
        );

        if ($terminate) {
            exit;
        }
    }
}
