<?php

namespace Fiserv\Tests\Services;

use Fiserv\Models\File;
use Fiserv\Repositories\FileRepository;
use Fiserv\Services\DatabaseService;
use Fiserv\Services\RecursiveFileReaderService;
use PHPUnit\Framework\TestCase;

class RecursiveFileReaderServiceTest extends TestCase
{
    private $tempDir;

    protected function setUp(): void
    {
        $this->tempDir = sys_get_temp_dir() . '/file_reader_test_' . uniqid();
        mkdir($this->tempDir);

        mkdir($this->tempDir . '/subdir');
        file_put_contents($this->tempDir . '/test.txt', 'A coding test for fiserv');
        file_put_contents($this->tempDir . '/subdir/test2.txt', 'Yet another file');
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
        $fileRepositoryMock = $this->createMock(FileRepository::class);

        $fileReader = new RecursiveFileReaderService($this->tempDir, $fileRepositoryMock);

        $readPaths = $fileReader->readDirectoryRecursively();

        $this->assertEquals([
            'subdir' => [
                'test2.txt' => 'test2.txt'
            ],
            'test.txt' => 'test.txt'
        ], $readPaths);
    }

    public function testCreatingFileTree()
    {
        $fileRepositoryMock = $this->createMock(FileRepository::class);

        $fileReader = new RecursiveFileReaderService($this->tempDir, $fileRepositoryMock);

        $readPaths = $fileReader->readDirectoryRecursively();
        $fileTree = $fileReader->createVisualFileTree($readPaths);

        // Our file-tree with indents and newlines
        $expected = "subdir\n\ttest2.txt\ntest.txt\n";

        $this->assertEquals($expected, $fileTree);
    }
}