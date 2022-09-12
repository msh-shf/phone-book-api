<?php

// src/Service/FileUploader.php
namespace App\Services;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private $publicDirectory;
    private $slugger;

    public function __construct(KernelInterface $kernel, SluggerInterface $slugger)
    {
        $this->publicDirectory = $kernel->getProjectDir() . '/public';
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file, $relativePath)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory($relativePath), $fileName);
        } catch (FileException $e) {
            return null;
        }

        return $relativePath . '/' . $fileName;
    }

    public function getTargetDirectory($relativePath)
    {
        return $this->publicDirectory . '/' . $relativePath;
    }
}