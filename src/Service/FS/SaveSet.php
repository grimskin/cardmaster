<?php


namespace App\Service\FS;


use Symfony\Component\Filesystem\Filesystem;

class SaveSet
{
    public function save(string $setName, string $setData)
    {
        $fileName = strtoupper($setName) . '.json';
        $path = __DIR__ . '/../../../data/';

        $fs = new Filesystem();
        $fs->dumpFile($path . $fileName, $setData);
    }
}
