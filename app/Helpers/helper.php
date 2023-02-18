<?php

/**
 * @param string $controller
 * @param string $method
 * @param array $data
 * 
 * @return string
 */
function route(string $controller, string $method, array $data = []): string
{
    return "?_c={$controller}&_m={$method}" . ($data ? '&' . http_build_query($data) : '');
}

/**
 * @param mixed $var
 * @param string|null $key
 * 
 * @return string|null
 */
function __($var, ?string $key = null): ?string
{
    if (!is_null($key)) {
        if (is_array($var)) {
            return $var[$key] ?? null;
        } else if (is_object($var)) {
            return $var->$key ?? null;
        }
    }

    return $var;
}
