<?php

namespace MDCR\core;

class File
{
    public static function save($file, $folder)
    {
        $targetDir = dirname(__DIR__) . "/../../storage/" . $folder;
        mkdir($targetDir, 0777, true);
        $fileName = date("Y-m-d_H-i-s_") . basename($file["name"]);
        $targetFile = $targetDir . '/' . $fileName;
        

        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            return $folder . '/' . $fileName;
        } else {
            return false;
        }

    }

    public static function get($path)
    {
        $targetDir = dirname(__DIR__) . "/../../storage/";
        $filePath = $targetDir . $path;
        $file = fopen($filePath, 'r') or die("Erro fatal ao abrir o arquivo $path");
        return $file;
    }

    public static function getBase64($path)
    {
        $targetDir = dirname(__DIR__) . "/../../storage/";
        $filePath = $targetDir . $path;
        $data = file_get_contents($filePath);
        $data64 = base64_encode($data);
        return $data64;
    }

    /**
     * Save base 64 string to file
     * @param String $base64
     */
    public static function saveBase64ToFile($base64, $folder = '', $extension = null)
    {
        $targetDir = dirname(__DIR__) . "/../../storage/" . $folder;
        mkdir($targetDir, 0777, true);
        
        if($extension == null) {
            $file = fopen("data:audio/mpeg;base64," . $base64, 'r');
            $extension = explode('/', mime_content_type($file))[1];
        }
        $fileName = date("Y-m-d_H-i-s_") . str_shuffle('abc0123') . '.' . $extension;
        $targetFile = $targetDir . '/' . $fileName;
        
        if (file_put_contents($targetFile, base64_decode($base64))) {
            return $folder . '/' . $fileName;
        } else {
            return false;
        }
    }
}