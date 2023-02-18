<?php

/**
 * @param string $controller
 * @param string $method
 * @param array $data
 *
 * @return string
 */
function route(string $controller, string $method, array $data = []) : string
{
    return "?_c={$controller}&_m={$method}".($data ? '&'.http_build_query($data) : '');
}

/**
 * @param mixed $var
 * @param string|null $key
 *
 * @return string|null
 */
function __($var, ?string $key = null) : ?string
{
    if (! is_null($key)) {
        if (is_array($var)) {
            return $var[$key] ?? null;
        } elseif (is_object($var)) {
            return $var->$key ?? null;
        }
    }

    return $var;
}

/**
 * @param array $data
 * @param array $allowed
 *
 * @return array|null
 */
function only(array $data, array $allowed) : ?array
{
    return array_filter($data, function ($key) use ($allowed) {
        return in_array($key, $allowed);
    }, ARRAY_FILTER_USE_KEY);
}

/**
 * @param array $data
 * @param array $ignored
 *
 * @return array|null
 */
function except(array $data, array $ignored) : ?array
{
    return array_filter($data, function ($key) use ($ignored) {
        return ! in_array($key, $ignored);
    }, ARRAY_FILTER_USE_KEY);
}
