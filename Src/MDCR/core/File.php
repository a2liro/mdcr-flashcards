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
}
