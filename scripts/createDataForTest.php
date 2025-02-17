<?php

use Fiserv\Services\RecursiveFileReaderService;

require __DIR__ . '/../vendor/autoload.php';

$fileReader = new RecursiveFileReaderService('/');
$files = $fileReader->readDirectoryRecursively();

foreach ($files as $key => $value) {
    if (is_dir())
}