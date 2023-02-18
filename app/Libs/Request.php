<?php

namespace Contrive\Deployer\Libs;

class Request
{
    private $request;
    private $files;

    public function __construct()
    {
        $this->request = $_REQUEST;
        $this->files = $_FILES;
    }

    /**
     * @param string $key
     *
     * @return string|null
     */
    public function get(string $key) : ?string
    {
        return $this->request[$key] ?? null;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function set(string $key, $value) : self
    {
        $this->request[$key] = $value;
        return $this;
    }

    /**
     * @return array|null
     */
    public function files() : ?array
    {
        return $this->files;
    }

    /**
     * @param string $name
     * @return array|null
     */
    public function file(string $name) : ?array
    {
        return $this->files[$name] ?? null;
    }

    /**
     * @param array $allowed
     *
     * @return array|null
     */
    public function only(array $allowed) : ?array
    {
        return only($this->request, $allowed);
    }

    /**
     * @param array $ignored
     *
     * @return array|null
     */
    public function except(array $ignored) : ?array
    {
        return except($this->request, $ignored);
    }

    /**
     * @return array|null
     */
    public function all() : ?array
    {
        return $this->request;
    }

    /**
     * @param string $key
     *
     * @return string|null
     */
    public function __get(string $key) : ?string
    {
        return $this->get($key);
    }
}
