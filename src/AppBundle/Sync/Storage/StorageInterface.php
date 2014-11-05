<?php
namespace AppBundle\Sync\Storage;

interface StorageInterface
{
    public function listContents($directory = '');
    public function put($sourcePath, $destPath);
    public function delete($path);
}