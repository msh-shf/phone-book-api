<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    const STATUS_SUCCESS = 'success';
    const STATUS_ERROR = 'error';

    public function notFoundJsonResponse(?int $id = null) {
        $message = 'The requested resource not found' . ($id ? ", resource id: {$id}" : '');
        return $this->errorJsonResponse($message, 404);
    }

    public function successJsonResponse($data = null, $status_code = 200) {
        return $this->json([
            'status' => self::STATUS_SUCCESS,
            'data' => $data,
            'message' => null,
        ], $status_code);
    }

    public function errorJsonResponse($message, $status_code) {
        return $this->json([
            'status' => self::STATUS_ERROR,
            'data' => null,
            'message' => $message,
        ], $status_code);
    }

}
