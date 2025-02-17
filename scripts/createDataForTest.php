<?php

use Fiserv\Repositories\FileRepository;
use Fiserv\Services\DatabaseService;
use Fiserv\Services\RecursiveFileReaderService;

require __DIR__ . '/../vendor/autoload.php';

// We try to load from root by default (on a unix-based system)
$pathToLoad = '/';

if (!empty($argv[1])) {
    $pathToLoad = $argv[1];
}

echo "Trying to load file system/generating tree from {$pathToLoad}." . PHP_EOL;

$fileReader = new RecursiveFileReaderService($pathToLoad, new FileRepository(
    new DatabaseService()
));
$files = $fileReader->readDirectoryRecursively();

if (is_file('result.txt')) {
    unlink('result.txt');
}

file_put_contents('result.txt', $fileReader->createVisualFileTree($files));

$fileReader->storeFileTree($files, '');