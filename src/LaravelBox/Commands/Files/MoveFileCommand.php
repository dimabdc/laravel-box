<?php

namespace LaravelBox\Commands\Files;

use GuzzleHttp\Client;
use LaravelBox\Factories\ApiResponseFactory;
use LaravelBox\LaravelBox;

class MoveFileCommand extends AbstractFileCommand
{
    private $newPath;
    private $oldPath;

    public function __construct(LaravelBox $app, $path, $newPath)
    {
        $this->app = $app;
        $this->oldPath  = $path;
        $this->newPath  = $newPath;
        $this->fileId   = is_string($path) ? parent::getFileId($path) : $path;
        $this->folderId = is_string($newPath) ? parent::getFolderId(dirname($path)) : $newPath;
    }

    public function execute()
    {
        $url     = $this->app->getApiURI() . "/files/{$this->fileId}";
        $body    = [
            'name'   => basename($this->newPath),
            'parent' => [
                'id' => $this->getFolderId(dirname($this->newPath)),
            ],
        ];
        $options = [
            'headers' => [
                'Authorization' => "Bearer {$this->app->getToken()}",
            ],
            'body'    => json_encode($body),
        ];

        try {
            $client = new Client();
            $req    = $client->request('PUT', $url, $options);

            return ApiResponseFactory::build($req);
        } catch (\Exception $e) {
            return ApiResponseFactory::build($e);
        }
    }
}
