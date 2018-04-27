<?php

namespace LaravelBox;

use LaravelBox\Factories\AuthCommandFactory;
use LaravelBox\Factories\FileCommandFactory;
use LaravelBox\Factories\FolderCommandFactory;
use LaravelBox\Factories\StreamCommandFactory;
use LaravelBox\Helpers\FileAccessTokenCommand;
use LaravelBox\Helpers\FolderItemCount;

class LaravelBox
{
    private $config = [
        'token' => '',
        'apiRootURL' => 'https://api.box.com',
        'apiVersion' => '2.0'
    ];

    public function __construct(array $config)
    {
        $this->setConfig($config);
    }

    public function setConfig(array $config)
    {
        foreach ($config as $key => $value) {
            $this->config[$key] = $value;
        }

        return $this;
    }

    public function getToken()
    {
        return $this->config['token'];
    }

    public function getApiURI(): string
    {
        return $this->getApiRootURL() . '/' . $this->getApiVersion();
    }

    public function getApiRootURL()
    {
        return $this->config['apiRootURL'];
    }

    public function getApiVersion()
    {
        return $this->config['apiVersion'];
    }

    public function moveFile($path, $newPath)
    {
        $command = FileCommandFactory::build($this, $path, $newPath, 'move');

        return $command->execute();
    }

    public function fileInformation(string $path)
    {
        $command = FileCommandFactory::build($this, $path, 'info');

        return $command->execute();
    }

    public function fileDownload($remotePath)
    {
        $command = FileCommandFactory::build($this, $remotePath, 'download');

        return $command->execute();
    }

    public function fileStreamDownload(string $remotePath)
    {
        $command = StreamCommandFactory::build($this, $remotePath, 'download');

        return $command->execute();
    }

    public function uploadFile($localPath, string $remotePath)
    {
        $command = FileCommandFactory::build($this, $localPath, $remotePath, 'upload');

        return $command->execute();
    }

    public function uploadContents($contents, string $remotePath)
    {
        $command = StreamCommandFactory::build($this, $contents, $remotePath, 'upload');

        return $command->execute();
    }

    public function uploadFileVersion(string $localPath, string $remotePath)
    {
        $command = FileCommandFactory::build($this, $localPath, $remotePath, 'upload-version');

        return $command->execute();
    }

    public function uploadContentsVersion($contents, string $remotePath)
    {
        $command = StreamCommandFactory::build($this, $contents, $remotePath, 'upload-version');

        return $command->execute();
    }

    public function uploadStreamContents($resource, string $remotePath)
    {
        $command = StreamCommandFactory::build($this, $resource, $remotePath, 'upload-stream');

        return $command->execute();
    }

    public function uploadStreamContentsVersion($resource, string $remotePath)
    {
        $command = StreamCommandFactory::build($this, $resource, $remotePath, 'upload-stream-version');

        return $command->execute();
    }

    public function preflightCheck(string $localPath, string $remotePath)
    {
        $command = FileCommandFactory::build($this, $localPath, $remotePath, 'flight-check');

        return $command->execute();
    }

    public function deleteFile(string $path)
    {
        $command = FileCommandFactory::build($this, $path, 'delete');

        return $command->execute();
    }

    public function copyFile(string $path, string $newPath)
    {
        $command = FileCommandFactory::build($this, $path, $newPath, 'copy');

        return $command->execute();
    }

    public function lockFile(string $path)
    {
        $command = FileCommandFactory::build($this, $path, 'file-lock');

        return $command->execute();
    }

    public function unLockFile(string $path)
    {
        //TODO Does not actually Unlock because bad API
        $command = FileCommandFactory::build($this, $path, 'file-unlock');

        return $command->execute();
    }

    public function downloadFileThumbnail(string $path, string $outPath, string $extension = 'png')
    {
        $command = FileCommandFactory::build($this, $path, $outPath, $extension, 'thumbnail');

        return $command->execute();
    }

    public function fileThumbnailStream(string $path, string $extension = 'png')
    {
        $command = StreamCommandFactory::build($this, $path, $extension, 'thumbnail');

        return $command->execute();
    }

    public function fileEmbeddedLink(string $path)
    {
        $command = FileCommandFactory::build($this, $path, 'embed-link');

        return $command->execute();
    }

    public function fileComments(string $path)
    {
        $command = FileCommandFactory::build($this, $path, 'comments');

        return $command->execute();
    }

    public function fileTasks(string $path)
    {
        $command = FileCommandFactory::build($this, $path, 'tasks');

        return $command->execute();
    }

    public function deleteFolder(string $path, $recursive = false)
    {
        $command = FolderCommandFactory::build($this, $path, $recursive, 'delete');

        return $command->execute();
    }

    public function createFolder(string $path)
    {
        $command = FolderCommandFactory::build($this, $path, 'create');

        return $command->execute();
    }

    public function getFolderItems($path, int $offset = 0, int $limit = 100, string $fields = '')
    {
        $command = FolderCommandFactory::build($this, $path, $offset, $limit, $fields, 'list');

        return $command->execute();
    }

    public function getFolderInfo($path)
    {
        $command = FolderCommandFactory::build($this, $path, 'info');

        return $command->execute();
    }

    public function getFolderItemsCount(string $path)
    {
        $command = new FolderItemCount($this, $path);

        return $command->execute();
    }

    public function fileAccessToken($path)
    {
        $command = new FileAccessTokenCommand($this, $path);

        return $command->execute();
    }

    public function authToken(string $code, string $clientId, string $clientSecret)
    {
        $command = AuthCommandFactory::build($this, $code, $clientId, $clientSecret, 'token');

        return $command->execute();
    }

    public function refreshToken(string $token, string $clientId, string $clientSecret)
    {
        $command = AuthCommandFactory::build($this, $token, $clientId, $clientSecret, 'refresh');

        return $command->execute();
    }
}
