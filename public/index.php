<?php

require __DIR__ . '/../vendor/autoload.php';

use Fiserv\Services\RecursiveFileReaderService;

$x = new RecursiveFileReaderService('/');
$x->readDirectoryRecursively();