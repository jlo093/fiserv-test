<?php

namespace Fiserv\Repositories;

use Fiserv\Models\File;
use Fiserv\Services\DatabaseService;

class FileRepository implements FileRepositoryInterface
{
    public function __construct(private readonly DatabaseService $db) {}

    public function updateOrCreate(File $file): bool
    {
        $this->db->query(
            "INSERT INTO filesystem(path) VALUES(?) ON DUPLICATE KEY UPDATE path = ?",
            [
                $file->path, $file->path
            ]
        );
    }

    public function findByPath(string $path): array
    {
        return $this->db->fetchAll(
            'SELECT * FROM filesystem WHERE path LIKE ?',
            ['%' . $path . '%']
        );
    }
}