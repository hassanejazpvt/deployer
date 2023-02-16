<?php

namespace Contrive\Deployer\Controllers;

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
    public function JsonResponse($data, int $code = 200): ?string
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
    public function ValidateRequestMethod(string $method): void
    {
        if ($_SERVER['REQUEST_METHOD'] != strtoupper($method)) {
            if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
                $this->JsonResponse('Method Not Allowed', 405);
            http_response_code(405);
            exit();
        }
    }
}
