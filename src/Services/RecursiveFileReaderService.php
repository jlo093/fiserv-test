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
                $relativePath = $iterator->getSubPathName(); // Gets path relative to $directory

                if ($file->isDir()) {
                    // Ensure directories are keys in the array
                    $parts = explode(DIRECTORY_SEPARATOR, $relativePath);
                    $subArray = &$result;
                    foreach ($parts as $part) {
                        if (!isset($subArray[$part])) {
                            $subArray[$part] = [];
                        }
                        $subArray = &$subArray[$part];
                    }
                } else {
                    // Add files to the correct directory structure
                    $parts = explode(DIRECTORY_SEPARATOR, $relativePath);
                    $filename = array_pop($parts);
                    $subArray = &$result;
                    foreach ($parts as $part) {
                        if (!isset($subArray[$part])) {
                            $subArray[$part] = [];
                        }
                        $subArray = &$subArray[$part];
                    }
                    $subArray[] = $filename; // Files are stored as values
                }
            }
        } catch (\UnexpectedValueException $exception) {

        }

        return $readPaths;
    }
}