<?php

namespace LaravelBox\Commands\Files;

use GuzzleHttp\Client;
use LaravelBox\Factories\ApiResponseFactory;
use LaravelBox\LaravelBox;

class FileTasksCommand extends AbstractFileCommand
{
    public function __construct(LaravelBox $app, string $path)
    {
        $this->app = $app;
        $this->fileId = parent::getFileId($path);
        $this->folderId = parent::getFolderId(dirname($path));
    }

    public function execute()
    {
        $token = $this->token;
        $fileId = $this->fileId;

        $url = $this->app->getApiURI() . "/files/{$fileId}/tasks";
        $options = [
            'headers' => [
                'Authorization' => "Bearer {$this->app->getToken()}",
            ],
        ];

        try {
            $client = new Client();
            $req = $client->request('GET', $url, $options);

            return ApiResponseFactory::build($req);
        } catch (\Exception $e) {
            return ApiResponseFactory::build($e);
        }
    }
}
