<?php

namespace Fiserv\Tests\Services;

use Fiserv\Repositories\FileRepository;
use Fiserv\Services\DatabaseService;
use Fiserv\Services\RecursiveFileReaderService;
use PHPUnit\Framework\TestCase;

class RecursiveFileReaderServiceTest extends TestCase
{
    private $tempDir;
    private RecursiveFileReaderService $fileReader;

    protected function setUp(): void
    {
        $this->tempDir = sys_get_temp_dir() . '/file_reader_test_' . uniqid();
        mkdir($this->tempDir);

        mkdir($this->tempDir . '/subdir');
        file_put_contents($this->tempDir . '/test.txt', 'A coding test for fiserv');
        file_put_contents($this->tempDir . '/subdir/test2.txt', 'Yet another file');

        $this->fileReader = new RecursiveFileReaderService($this->tempDir, new FileRepository(
            new DatabaseService()
        ));
    }

    protected function tearDown(): void
    {
        $this->deleteDirectory($this->tempDir);
    }

    private function deleteDirectory($dir): void
    {
        if (!file_exists($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = "$dir/$file";
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }

    public function testRecursiveReading()
    {
        $readPaths = $this->fileReader->readDirectoryRecursively();

        $this->assertEquals([
            'subdir' => [
                'test2.txt'
            ],
            'test.txt'
        ], $readPaths);
    }
}