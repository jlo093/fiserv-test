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
        $readPaths = []; // Ensure the result array is initialized

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

                $current = &$readPaths;

                foreach ($parts as $part) {
                    if (!isset($current[$part])) {
                        $current[$part] = [];
                    }
                    $current = &$current[$part];
                }

                if (!$file->isDir()) {
                    $current = $file->getFilename();
                }
            }
        } catch (\UnexpectedValueException $exception) {
            // Handle exception properly if needed
        }

        return $readPaths;
    }

    public function createVisualFileTree(array $files, int $depth = 0): string
    {
        $output = '';

        foreach ($files as $key => $value) {
            if (is_array($value)) {
                $output .= str_repeat("\t", $depth) . $key . "\n";
                $output .= $this->createVisualFileTree($value, $depth + 1);
            } else {
                $output .= str_repeat("\t", $depth) . $value . "\n";
            }
        }

        return $output;
    }
}