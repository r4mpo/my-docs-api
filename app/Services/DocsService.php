<?php

namespace App\Services;

use App\Interfaces\FilesInterface;

class DocsService implements FilesInterface
{
    public function saveFile($request, $path)
    {
        $file = $request->file;
        $extension = $file->extension();
        $fileName = md5($file->getClientOriginalName() . strtotime("now") . "." . $extension);
        $request->file->move(public_path($path), $fileName);

        return $fileName;
    }

    public function updateFile($data, $fileBase64, $path)
    {
        // Caso tenha um arquivo existente no documento...
        if (!empty($data->file)) {
            $this->removeFile($data->file, $path);
        }

        // Separar a string Base64 e obter os dados binários
        list($type, $data) = explode(';', $fileBase64);
        list(, $data)      = explode(',', $data);
        list(, $extension) = explode('/', $type);
        $data = base64_decode($data);

        $fileName = md5(substr($fileBase64, 0, 15) . strtotime("now") . $extension);

        // Salvar os dados binários como um arquivo de imagem
        file_put_contents($path . $fileName, $data);

        return $fileName;
    }

    public function removeFile($file, $path)
    {
        // Capturando o arquivo antigo relacionado ao documento
        $previousFile = public_path($path . $file);

        // Excluindo arquivo antigo do documento
        if (file_exists($previousFile)) {
            unlink($previousFile);
        }
    }
}
