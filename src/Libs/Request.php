<?php

namespace Contrive\Deployer\Libs;

class Request
{
    private $request;

    public function __construct()
    {
        $this->request = $_REQUEST;
    }

    /**
     * @param string $key
     *
     * @return string|null
     */
    public function get(string $key): ?string
    {
        return $this->request[$key] ?? null;
    }

    /**
     * @param array $allowed
     *
     * @return array|null
     */
    public function only(array $allowed): ?array
    {
        return array_filter($this->request, function ($key) use ($allowed) {
            return in_array($key, $allowed);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @param array $ignored
     * 
     * @return array|null
     */
    public function except(array $ignored): ?array
    {
        return array_filter($this->request, function ($key) use ($ignored) {
            return !in_array($key, $ignored);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @return array|null
     */
    public function all(): ?array
    {
        return $this->request;
    }

    /**
     * @param string $key
     * 
     * @return string|null
     */
    public function __get(string $key): ?string
    {
        return $this->get($key);
    }
}
