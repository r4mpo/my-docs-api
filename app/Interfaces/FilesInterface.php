<?php

namespace App\Interfaces;

interface FilesInterface
{
    public function saveFile($request, $path);
    public function updateFile($data, $fileBase64, $path);
    public function removeFile($file, $path);
}