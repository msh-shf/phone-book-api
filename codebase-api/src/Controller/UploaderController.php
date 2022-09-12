<?php

namespace App\Controller;

use App\Services\FileUploader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UploaderController extends BaseController
{
    #[Route('/upload/image', name: 'upload_image', methods: ["POST"])]
    public function image(Request $request, FileUploader $fileUploader): JsonResponse
    {
        if($fileName = $fileUploader->upload($request->files->get('image'), $path = 'images')) {
            return $this->successJsonResponse($fileName);
        } else {
            return $this->errorJsonResponse('An error occurred!', 400);
        }
    }
}
