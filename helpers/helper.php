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
