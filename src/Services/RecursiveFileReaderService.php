<?php

namespace Fiserv\Services;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

readonly class RecursiveFileReaderService
{
    public function __construct(private string $rootDirectory) {}

    public function readDirectoryRecursively(): array
    {
        $readPaths = [];

        try {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(
                    $this->rootDirectory,
                    FilesystemIterator::SKIP_DOTS
                ),
                RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($iterator as $file) {
                $relativePath = $iterator->getSubPathName();

                $parts = explode(DIRECTORY_SEPARATOR, $relativePath);
                if ($file->isDir()) {
                    $readPaths = &$result;
                    foreach ($parts as $part) {
                        if (!isset($readPaths[$part])) {
                            $readPaths[$part] = [];
                        }
                        $readPaths = &$readPaths[$part];
                    }
                } else {
                    $filename = array_pop($parts);
                    $readPaths = &$result;
                    foreach ($parts as $part) {
                        if (!isset($readPaths[$part])) {
                            $readPaths[$part] = [];
                        }
                        $readPaths = &$readPaths[$part];
                    }
                    $readPaths[] = $filename;
                }
            }
        } catch (\UnexpectedValueException $exception) {

        }

        return $readPaths;
    }
}