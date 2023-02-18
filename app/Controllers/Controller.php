<?php

namespace Contrive\Deployer\Controllers;

use Exception;

class Controller
{
    public function __construct()
    {
    }

    /**
     * @param mixed $data
     * @param integer $code
     *
     * @return string|null
     */
    public function JsonResponse($data, int $code = 200) : ?string
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        die;
    }

    /**
     * @param string $method
     *
     * @return void
     */
    public function ValidateRequestMethod(string $method) : void
    {
        if ($_SERVER['REQUEST_METHOD'] != strtoupper($method)) {
            if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
                $this->JsonResponse('Method "'.$_SERVER['REQUEST_METHOD'].'" Not Allowed', 405);
            }
            throw new Exception('Method "'.$_SERVER['REQUEST_METHOD'].'" Not Allowed', 405);
            exit();
        }
    }

    /**
     * @param array $methods
     *
     * @return void
     */
    public function ValidateRequestMethods(array $methods) : void
    {
        if (isset($methods[$_GET['_m']])) {
            $this->ValidateRequestMethod($methods[$_GET['_m']]);
        }
    }
}
