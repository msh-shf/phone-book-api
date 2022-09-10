<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    public function notFoundJsonResponse(?int $id = null) {
        return $this->json([
            'error' => 'The requested resource not found' . ($id ? ", resource id: {$id}" : '')
        ], 404);
    }
}
