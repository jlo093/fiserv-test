<?php

namespace Fiserv\Repositories;

use Fiserv\Models\File;

interface FileRepositoryInterface
{
    public function updateOrCreate(File $file): bool;
    public function findByPath(string $path): array;
}